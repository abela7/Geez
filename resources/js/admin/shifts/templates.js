/**
 * Templates Page JavaScript
 * Handles template management, creation, editing, and application
 */

// Templates Index Page Component
function templatesPageData(initialTemplates = [], initialShifts = [], initialStaff = []) {
    return {
        // Data
        templates: initialTemplates,
        searchQuery: '',
        filterType: 'all',
        filterStatus: 'all',
        sortBy: 'created_at',

        // UI State
        activeDropdown: null,
        isLoading: false,

        // Computed
        get filteredTemplates() {
            return this.templates.filter(template => {
                const matchesSearch = !this.searchQuery ||
                    template.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    template.description?.toLowerCase().includes(this.searchQuery.toLowerCase());

                const matchesType = this.filterType === 'all' || template.type === this.filterType;
                const matchesStatus = this.filterStatus === 'all' || template.status === this.filterStatus;

                return matchesSearch && matchesType && matchesStatus;
            }).sort((a, b) => {
                switch (this.sortBy) {
                    case 'name':
                        return a.name.localeCompare(b.name);
                    case 'usage_count':
                        return b.usage_count - a.usage_count;
                    case 'type':
                        return a.type.localeCompare(b.type);
                    case 'created_at':
                    default:
                        return new Date(b.created_at) - new Date(a.created_at);
                }
            });
        },

        // Methods
        init() {
            console.log('Templates page initialized');
        },
        
        filterTemplates() {
            // Filtering is handled by computed property
        },

        isTemplateVisible(templateId) {
            return this.filteredTemplates.some(t => t.id === templateId);
        },

        toggleDropdown(templateId) {
            this.activeDropdown = this.activeDropdown === templateId ? null : templateId;
        },

        async duplicateTemplate(templateId, templateName) {
            if (!confirm(`Are you sure you want to duplicate "${templateName}"?`)) {
                return;
            }
            
            this.isLoading = true;
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Reload page to show new template
                    window.location.reload();
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to duplicate template', 'error');
            } finally {
                this.isLoading = false;
                this.activeDropdown = null;
            }
        },

        async setAsDefault(templateId) {
            this.isLoading = true;
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}/set-default`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Update templates
                    this.templates = this.templates.map(template => ({
                        ...template,
                        is_default: template.id === templateId
                    }));
            } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to set template as default', 'error');
            } finally {
                this.isLoading = false;
                this.activeDropdown = null;
            }
        },

        async toggleTemplateStatus(templateId) {
            this.isLoading = true;
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}/toggle-active`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Update template status
                    this.templates = this.templates.map(template =>
                        template.id === templateId
                            ? { ...template, status: template.is_active ? 'draft' : 'active', is_active: !template.is_active }
                            : template
                    );
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to toggle template status', 'error');
            } finally {
                this.isLoading = false;
                this.activeDropdown = null;
            }
        },

        async deleteTemplate(templateId, templateName) {
            if (!confirm(`Are you sure you want to delete "${templateName}"? This action cannot be undone.`)) {
                return;
            }

            this.isLoading = true;
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    this.showNotification('Template deleted successfully', 'success');
                    // Remove from templates array
                    this.templates = this.templates.filter(template => template.id !== templateId);
                } else {
                    const data = await response.json();
                    this.showNotification(data.message || 'Failed to delete template', 'error');
                }
            } catch (e) {
                this.showNotification('Failed to delete template', 'error');
            } finally {
                this.isLoading = false;
                this.activeDropdown = null;
            }
        },

        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <p class="notification-message">${message}</p>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}

// Template Create Page Component
function templateCreateData() {
    return {
        // Template data
        templateData: {
            name: '',
            description: '',
            type: 'standard',
            is_active: true,
            is_default: false
        },

        // Assignments data
        assignments: [], // Array of assignments by day (0-6)

        // UI State
        activeDay: 0, // Currently selected day (0=Sunday)
        showAddAssignmentModal: false,
        editingAssignment: null, // Index of assignment being edited
        isSubmitting: false,

        // Form data for modal
        assignmentForm: {
            staff_shift_id: '',
            staff_id: '',
            status: 'scheduled',
            notes: ''
        },

        // Reference data (passed from controller)
        shifts: initialShifts,
        staff: initialStaff,

        // Computed
        getTotalAssignments() {
            return this.assignments.flat().length;
        },

        getUniqueStaffCount() {
            const staffIds = new Set();
            this.assignments.forEach(day => {
                day.forEach(assignment => staffIds.add(assignment.staff_id));
            });
            return staffIds.size;
        },

        getUniqueShiftsCount() {
            const shiftIds = new Set();
            this.assignments.forEach(day => {
                day.forEach(assignment => shiftIds.add(assignment.staff_shift_id));
            });
            return shiftIds.size;
        },

        calculateEstimatedCost() {
            let totalCost = 0;
            this.assignments.forEach(day => {
                day.forEach(assignment => {
                    const shift = this.getShiftData(assignment.staff_shift_id);
                    if (shift) {
                        // Simple calculation: assume 8 hours at £15/hour
                        const hours = 8; // Could be more sophisticated
                        totalCost += hours * 15;
                    }
                });
            });
            return totalCost.toFixed(2);
        },

        // Methods
        init() {
            // Initialize assignments array for 7 days
            this.assignments = Array.from({ length: 7 }, () => []);

            console.log('Template create page initialized');
        },

        getAssignmentsForDay(dayIndex) {
            return this.assignments[dayIndex] || [];
        },

        getDayName(dayIndex) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[dayIndex] || 'Unknown';
        },

        getShiftName(shiftId) {
            const shift = this.getShiftData(shiftId);
            return shift ? shift.name : 'Unknown Shift';
        },

        getShiftTime(shiftId) {
            const shift = this.getShiftData(shiftId);
            return shift ? `${shift.start_time} - ${shift.end_time}` : '';
        },

        getStaffName(staffId) {
            const staff = this.staff.find(s => s.id == staffId);
            return staff ? staff.full_name : 'Unknown Staff';
        },

        getStaffType(staffId) {
            const staff = this.staff.find(s => s.id == staffId);
            return staff ? (staff.staffType?.display_name || 'No Type') : '';
        },

        getShiftData(shiftId) {
            for (const department of Object.values(this.shifts)) {
                const shift = department.find(s => s.id == shiftId);
                if (shift) return shift;
            }
            return null;
        },
        
        addAssignment() {
            this.editingAssignment = null;
            this.resetAssignmentForm();
            this.showAddAssignmentModal = true;
        },

        editAssignment(dayIndex, assignmentIndex) {
            this.editingAssignment = { dayIndex, assignmentIndex };
            const assignment = this.assignments[dayIndex][assignmentIndex];
            this.assignmentForm = { ...assignment };
            this.showAddAssignmentModal = true;
        },

        removeAssignment(dayIndex, assignmentIndex) {
            if (confirm('Are you sure you want to remove this assignment?')) {
                this.assignments[dayIndex].splice(assignmentIndex, 1);
            }
        },

        clearAllAssignments() {
            if (confirm('Are you sure you want to clear all assignments?')) {
                this.assignments = Array.from({ length: 7 }, () => []);
            }
        },

        saveAssignment() {
            if (!this.assignmentForm.staff_shift_id || !this.assignmentForm.staff_id) {
                alert('Please select both a shift and staff member.');
                return;
            }

            const assignment = { ...this.assignmentForm };

            if (this.editingAssignment) {
                // Update existing assignment
                const { dayIndex, assignmentIndex } = this.editingAssignment;
                this.assignments[dayIndex][assignmentIndex] = assignment;
            } else {
                // Add new assignment to current day
                if (!this.assignments[this.activeDay]) {
                    this.assignments[this.activeDay] = [];
                }
                this.assignments[this.activeDay].push(assignment);
            }

            this.closeAssignmentModal();
        },

        closeAssignmentModal() {
            this.showAddAssignmentModal = false;
            this.editingAssignment = null;
            this.resetAssignmentForm();
        },

        resetAssignmentForm() {
            this.assignmentForm = {
                staff_shift_id: '',
                staff_id: '',
                status: 'scheduled',
                notes: ''
            };
        },

        isFormValid() {
            return this.templateData.name.trim() &&
                   this.templateData.type &&
                   this.getTotalAssignments() > 0;
        },

        async submitTemplate() {
            if (!this.isFormValid()) {
                alert('Please fill in all required fields and add at least one assignment.');
                return;
            }

            this.isSubmitting = true;
            try {
                // Prepare form data
                const formData = {
                    ...this.templateData,
                    assignments: []
                };

                // Flatten assignments with day information
                this.assignments.forEach((dayAssignments, dayIndex) => {
                    dayAssignments.forEach(assignment => {
                        formData.assignments.push({
                            ...assignment,
                            day_of_week: dayIndex
                        });
                    });
                });

                const response = await fetch('/admin/shifts/templates', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                if (data.success) {
                    window.location.href = '/admin/shifts/templates';
                } else {
                    alert(data.message || 'Failed to create template');
                }
            } catch (e) {
                alert('Failed to create template: ' + e.message);
            } finally {
                this.isSubmitting = false;
            }
        },

        showNotification(message, type = 'info') {
            // Same notification function as index page
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <p class="notification-message">${message}</p>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}

// Template Edit Page Component
function templateEditData(templateId, existingAssignments = []) {
    return {
        // Template data
        templateData: {
            name: '',
            description: '',
            type: 'standard',
            is_active: true,
            is_default: false
        },

        // Assignments data
        assignments: Array.from({ length: 7 }, () => []), // Initialize empty arrays for each day

        // UI State
        activeDay: 0,
        showAddAssignmentModal: false,
        editingAssignment: null,
        isSubmitting: false,

        // Form data for modal
        assignmentForm: {
            id: null, // For existing assignments
            staff_shift_id: '',
            staff_id: '',
            status: 'scheduled',
            notes: ''
        },

        // Reference data
        shifts: [],
        staff: [],

        // Methods
        init() {
            // Load existing assignments
            const assignments = existingAssignments || [];
            assignments.forEach(assignment => {
                const dayIndex = assignment.day_of_week;
                if (!this.assignments[dayIndex]) {
                    this.assignments[dayIndex] = [];
                }
                this.assignments[dayIndex].push({
                    id: assignment.id,
                    staff_shift_id: assignment.staff_shift_id,
                    staff_id: assignment.staff_id,
                    status: assignment.status,
                    notes: assignment.notes || ''
                });
            });

            console.log('Template edit page initialized');
        },

        // Same methods as create component, but with update functionality
        getAssignmentsForDay(dayIndex) {
            return this.assignments[dayIndex] || [];
        },

        getDayName(dayIndex) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[dayIndex] || 'Unknown';
        },

        getShiftName(shiftId) {
            const shift = this.getShiftData(shiftId);
            return shift ? shift.name : 'Unknown Shift';
        },

        getShiftTime(shiftId) {
            const shift = this.getShiftData(shiftId);
            return shift ? `${shift.start_time} - ${shift.end_time}` : '';
        },

        getStaffName(staffId) {
            const staff = this.staff.find(s => s.id == staffId);
            return staff ? staff.full_name : 'Unknown Staff';
        },

        getStaffType(staffId) {
            const staff = this.staff.find(s => s.id == staffId);
            return staff ? (staff.staffType?.display_name || 'No Type') : '';
        },

        getShiftData(shiftId) {
            for (const department of Object.values(this.shifts)) {
                const shift = department.find(s => s.id == shiftId);
                if (shift) return shift;
            }
            return null;
        },

        getTotalAssignments() {
            return this.assignments.flat().length;
        },

        getUniqueStaffCount() {
            const staffIds = new Set();
            this.assignments.forEach(day => {
                day.forEach(assignment => staffIds.add(assignment.staff_id));
            });
            return staffIds.size;
        },

        getUniqueShiftsCount() {
            const shiftIds = new Set();
            this.assignments.forEach(day => {
                day.forEach(assignment => shiftIds.add(assignment.staff_shift_id));
            });
            return shiftIds.size;
        },

        calculateEstimatedCost() {
            let totalCost = 0;
            this.assignments.forEach(day => {
                day.forEach(assignment => {
                    const shift = this.getShiftData(assignment.staff_shift_id);
                    if (shift) {
                        const hours = 8; // Could be more sophisticated
                        totalCost += hours * 15;
                    }
                });
            });
            return totalCost.toFixed(2);
        },

        addAssignment() {
            this.editingAssignment = null;
            this.resetAssignmentForm();
            this.showAddAssignmentModal = true;
        },

        editAssignment(dayIndex, assignmentIndex) {
            this.editingAssignment = { dayIndex, assignmentIndex };
            const assignment = this.assignments[dayIndex][assignmentIndex];
            this.assignmentForm = { ...assignment };
            this.showAddAssignmentModal = true;
        },

        removeAssignment(dayIndex, assignmentIndex) {
            if (confirm('Are you sure you want to remove this assignment?')) {
                this.assignments[dayIndex].splice(assignmentIndex, 1);
            }
        },

        clearAllAssignments() {
            if (confirm('Are you sure you want to clear all assignments?')) {
                this.assignments = Array.from({ length: 7 }, () => []);
            }
        },

        saveAssignment() {
            if (!this.assignmentForm.staff_shift_id || !this.assignmentForm.staff_id) {
                alert('Please select both a shift and staff member.');
                return;
            }

            const assignment = { ...this.assignmentForm };

            if (this.editingAssignment) {
                // Update existing assignment
                const { dayIndex, assignmentIndex } = this.editingAssignment;
                this.assignments[dayIndex][assignmentIndex] = assignment;
            } else {
                // Add new assignment to current day
                if (!this.assignments[this.activeDay]) {
                    this.assignments[this.activeDay] = [];
                }
                this.assignments[this.activeDay].push(assignment);
            }

            this.closeAssignmentModal();
        },

        closeAssignmentModal() {
            this.showAddAssignmentModal = false;
            this.editingAssignment = null;
            this.resetAssignmentForm();
        },

        resetAssignmentForm() {
            this.assignmentForm = {
                id: null,
                staff_shift_id: '',
                staff_id: '',
                status: 'scheduled',
                notes: ''
            };
        },

        isFormValid() {
            return this.templateData.name.trim() &&
                   this.templateData.type &&
                   this.getTotalAssignments() > 0;
        },

        async submitTemplate() {
            if (!this.isFormValid()) {
                alert('Please fill in all required fields and add at least one assignment.');
                return;
            }

            this.isSubmitting = true;
            try {
                // Prepare form data
                const formData = {
                    ...this.templateData,
                    assignments: []
                };

                // Flatten assignments with day information
                this.assignments.forEach((dayAssignments, dayIndex) => {
                    dayAssignments.forEach(assignment => {
                        formData.assignments.push({
                            ...assignment,
                            day_of_week: dayIndex
                        });
                    });
                });

                const response = await fetch(`/admin/shifts/templates/${templateId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = '/admin/shifts/templates';
                } else {
                    alert(data.message || 'Failed to update template');
                }
            } catch (e) {
                alert('Failed to update template: ' + e.message);
            } finally {
                this.isSubmitting = false;
            }
        },

        async duplicateTemplate(templateId, templateName) {
            if (!confirm(`Are you sure you want to duplicate "${templateName}"?`)) {
                return;
            }

            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = `/admin/shifts/templates/${data.new_template_id}/edit`;
                } else {
                    alert(data.message || 'Failed to duplicate template');
                }
            } catch (e) {
                alert('Failed to duplicate template: ' + e.message);
            }
        },
        
        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <p class="notification-message">${message}</p>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}

// Template Show Page Component
function templateShowData(templateId) {
    return {
        templateId: templateId,
        isLoading: false,
        
        init() {
            console.log('Template show page initialized');
        },
        
        async applyTemplate(templateName) {
            if (!confirm(`Are you sure you want to apply "${templateName}" to the current week?`)) {
                return;
            }
            
            this.isLoading = true;
            try {
                // Get current week start date
                const urlParams = new URLSearchParams(window.location.search);
                const weekStart = urlParams.get('week') || new Date().toISOString().split('T')[0];

                const response = await fetch(`/admin/shifts/templates/${this.templateId}/apply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        week_start: weekStart,
                        overwrite_existing: false
                    })
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Redirect to assignments page
                setTimeout(() => {
                        window.location.href = '/admin/shifts/assignments';
                    }, 1000);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to apply template', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async setAsDefault() {
            this.isLoading = true;
            try {
                const response = await fetch(`/admin/shifts/templates/${this.templateId}/set-default`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to set template as default', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async duplicateTemplate(templateId, templateName) {
            if (!confirm(`Are you sure you want to duplicate "${templateName}"?`)) {
                return;
            }

            this.isLoading = true;
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification('Template duplicated successfully', 'success');
                    // Redirect to edit the new template
                    setTimeout(() => {
                        window.location.href = `/admin/shifts/templates/${data.new_template_id}/edit`;
                    }, 1000);
                } else {
                    this.showNotification(data.message, 'error');
                }
            } catch (e) {
                this.showNotification('Failed to duplicate template', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <p class="notification-message">${message}</p>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 5000);
        }
    };
}
