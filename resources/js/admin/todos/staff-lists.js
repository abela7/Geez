/**
 * Staff Lists JavaScript
 * Handles staff management, filtering, and todo operations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize staff lists functionality
    initializeStaffLists();
});

function initializeStaffLists() {
    // Initialize Alpine.js data
    window.staffListsData = function() {
        return {
            // Filter state
            filters: {
                department: 'All',
                role: 'All',
                status: 'All',
                search: ''
            },
            
            // Assign form state
            assignForm: {
                staff_id: '',
                title: '',
                description: '',
                priority: 'normal',
                due_date: '',
                estimated_duration: 30
            },
            
            // Staff data (will be populated from server)
            staffMembers: [],
            
            // Filtered staff
            filteredStaff: [],
            
            // Initialize
            init() {
                this.loadStaffData();
                this.applyFilters();
            },
            
            // Load staff data from server
            loadStaffData() {
                // In a real implementation, this would fetch from the server
                // For now, we'll use the data from the Blade template
                this.staffMembers = window.staffData || [];
                this.filteredStaff = [...this.staffMembers];
            },
            
            // Apply filters to staff list
            applyFilters() {
                this.filteredStaff = this.staffMembers.filter(staff => {
                    // Department filter
                    if (this.filters.department !== 'All' && staff.department !== this.filters.department) {
                        return false;
                    }
                    
                    // Role filter
                    if (this.filters.role !== 'All' && staff.role !== this.filters.role) {
                        return false;
                    }
                    
                    // Status filter
                    if (this.filters.status !== 'All' && staff.status !== this.filters.status.toLowerCase()) {
                        return false;
                    }
                    
                    // Search filter
                    if (this.filters.search) {
                        const searchTerm = this.filters.search.toLowerCase();
                        const searchableText = `${staff.name} ${staff.role} ${staff.department}`.toLowerCase();
                        if (!searchableText.includes(searchTerm)) {
                            return false;
                        }
                    }
                    
                    return true;
                });
            },
            
            // Clear all filters
            clearFilters() {
                this.filters = {
                    department: 'All',
                    role: 'All',
                    status: 'All',
                    search: ''
                };
                this.applyFilters();
            },
            
            // Check if staff should be visible based on filters
            isStaffVisible(staff) {
                return this.filteredStaff.some(filteredStaff => filteredStaff.id === staff.id);
            },
            
            // Get progress color based on completion rate
            getProgressColor(rate) {
                if (rate >= 90) return 'progress-excellent';
                if (rate >= 70) return 'progress-good';
                return 'progress-poor';
            },
            
            // Start a todo
            startTodo(todoId) {
                this.updateTodoStatus(todoId, 'in_progress');
            },
            
            // Complete a todo
            completeTodo(todoId) {
                this.updateTodoStatus(todoId, 'completed');
            },
            
            // Update todo status
            updateTodoStatus(todoId, status) {
                // Show loading state
                this.showNotification('Updating todo status...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    // Update local data
                    this.staffMembers.forEach(staff => {
                        staff.todos.forEach(todo => {
                            if (todo.id === todoId) {
                                todo.status = status;
                                if (status === 'completed') {
                                    todo.completed_at = new Date().toISOString();
                                }
                            }
                        });
                        
                        // Recalculate metrics
                        this.recalculateStaffMetrics(staff);
                    });
                    
                    this.applyFilters();
                    this.showNotification(`Todo ${status} successfully!`, 'success');
                }, 1000);
            },
            
            // Recalculate staff metrics
            recalculateStaffMetrics(staff) {
                const totalTodos = staff.todos.length;
                const completedTodos = staff.todos.filter(todo => todo.status === 'completed').length;
                const overdueTodos = staff.todos.filter(todo => 
                    todo.status !== 'completed' && 
                    new Date(todo.due_date) < new Date()
                ).length;
                
                staff.total_todos = totalTodos;
                staff.completed_todos = completedTodos;
                staff.overdue_todos = overdueTodos;
                staff.completion_rate = totalTodos > 0 ? Math.round((completedTodos / totalTodos) * 100) : 0;
            },
            
            // Open assign modal
            openAssignModal() {
                // Set default due date to tomorrow
                const tomorrow = new Date();
                tomorrow.setDate(tomorrow.getDate() + 1);
                tomorrow.setHours(9, 0, 0, 0);
                
                this.assignForm.due_date = tomorrow.toISOString().slice(0, 16);
                this.assignForm.estimated_duration = 30;
                
                // Show modal
                this.$nextTick(() => {
                    const modal = document.querySelector('[x-data*="showAssignModal"]');
                    if (modal) {
                        modal._x_dataStack[0].showAssignModal = true;
                    }
                });
            },
            
            // Assign todo to staff
            assignTodo() {
                // Validate form
                if (!this.assignForm.staff_id || !this.assignForm.title || !this.assignForm.due_date) {
                    this.showNotification('Please fill in all required fields', 'error');
                    return;
                }
                
                // Show loading state
                this.showNotification('Assigning todo...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    // Add todo to staff member
                    const staff = this.staffMembers.find(s => s.id == this.assignForm.staff_id);
                    if (staff) {
                        const newTodo = {
                            id: Date.now(),
                            title: this.assignForm.title,
                            description: this.assignForm.description,
                            priority: this.assignForm.priority,
                            status: 'pending',
                            due_date: this.assignForm.due_date,
                            completed_at: null,
                            recurring: false,
                            frequency: 'once',
                            template_id: null,
                            estimated_duration: this.assignForm.estimated_duration,
                            actual_duration: null
                        };
                        
                        staff.todos.push(newTodo);
                        this.recalculateStaffMetrics(staff);
                        this.applyFilters();
                    }
                    
                    // Reset form
                    this.assignForm = {
                        staff_id: '',
                        title: '',
                        description: '',
                        priority: 'normal',
                        due_date: '',
                        estimated_duration: 30
                    };
                    
                    // Hide modal
                    const modal = document.querySelector('[x-data*="showAssignModal"]');
                    if (modal) {
                        modal._x_dataStack[0].showAssignModal = false;
                    }
                    
                    this.showNotification('Todo assigned successfully!', 'success');
                }, 1000);
            },
            
            // Show notification
            showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification notification-${type}`;
                notification.innerHTML = `
                    <div class="notification-content">
                        <span class="notification-message">${message}</span>
                        <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;
                
                // Add to page
                document.body.appendChild(notification);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 5000);
            }
        };
    };
    
    // Initialize notification styles
    initializeNotifications();
}

function initializeNotifications() {
    // Add notification styles if not already present
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 1rem;
                right: 1rem;
                z-index: 1001;
                max-width: 400px;
                border-radius: var(--border-radius-lg);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                animation: slideIn 0.3s ease-out;
            }
            
            .notification-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 1rem;
                color: white;
                font-weight: 500;
            }
            
            .notification-info {
                background: var(--color-primary);
            }
            
            .notification-success {
                background: var(--color-success);
            }
            
            .notification-error {
                background: var(--color-danger);
            }
            
            .notification-warning {
                background: var(--color-warning);
            }
            
            .notification-message {
                flex: 1;
                margin-right: 0.75rem;
            }
            
            .notification-close {
                background: none;
                border: none;
                color: white;
                cursor: pointer;
                padding: 0.25rem;
                border-radius: var(--border-radius);
                transition: background-color 0.2s ease;
            }
            
            .notification-close:hover {
                background: rgba(255, 255, 255, 0.2);
            }
            
            .notification-close svg {
                width: 1rem;
                height: 1rem;
            }
            
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

// Export functions for global access
window.staffListsData = window.staffListsData || function() {
    return {
        filters: { department: 'All', role: 'All', status: 'All', search: '' },
        assignForm: { staff_id: '', title: '', description: '', priority: 'normal', due_date: '', estimated_duration: 30 },
        staffMembers: [],
        filteredStaff: [],
        init() {},
        loadStaffData() {},
        applyFilters() {},
        clearFilters() {},
        isStaffVisible() { return true; },
        getProgressColor() { return 'progress-excellent'; },
        startTodo() {},
        completeTodo() {},
        updateTodoStatus() {},
        recalculateStaffMetrics() {},
        openAssignModal() {},
        assignTodo() {},
        showNotification() {}
    };
};
