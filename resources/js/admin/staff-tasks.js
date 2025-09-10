/**
 * Staff Task Management JavaScript
 * Handles task creation, editing, filtering, drag-and-drop, and real-time updates
 */

// Task Manager Alpine.js Component
function taskManager() {
    return {
        // State Management
        currentView: 'dashboard',
        showTaskModal: false,
        editingTask: null,
        
        // Data
        tasks: [],
        assignees: [],
        availableTasks: [],
        recentTasks: [],
        
        // Filters & Search
        filters: {
            assignee: '',
            priority: '',
            category: '',
            status: ''
        },
        searchQuery: '',
        sortBy: 'created_at',
        sortOrder: 'desc',
        
        // Selection
        selectedTasks: [],
        
        // Form Data
        taskForm: {
            id: null,
            title: '',
            description: '',
            assignee_id: '',
            priority: 'medium',
            category: 'kitchen',
            due_date: '',
            estimated_hours: '',
            status: 'todo',
            is_recurring: false,
            send_notifications: true,
            recurrence_pattern: 'weekly',
            dependencies: [],
            attachments: []
        },
        
        // Computed Properties
        get taskStats() {
            const stats = {
                total: this.tasks.length,
                completed: 0,
                inProgress: 0,
                overdue: 0
            };
            
            this.tasks.forEach(task => {
                if (task.status === 'completed') stats.completed++;
                if (task.status === 'in_progress') stats.inProgress++;
                if (task.is_overdue) stats.overdue++;
            });
            
            return stats;
        },
        
        get filteredTasks() {
            let filtered = [...this.tasks];
            
            // Apply search filter
            if (this.searchQuery) {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(task => 
                    task.title.toLowerCase().includes(query) ||
                    task.description.toLowerCase().includes(query) ||
                    task.assignee.toLowerCase().includes(query)
                );
            }
            
            // Apply filters
            Object.keys(this.filters).forEach(key => {
                if (this.filters[key]) {
                    filtered = filtered.filter(task => task[key] === this.filters[key]);
                }
            });
            
            // Apply sorting
            filtered.sort((a, b) => {
                let aValue = a[this.sortBy];
                let bValue = b[this.sortBy];
                
                // Handle priority sorting
                if (this.sortBy === 'priority') {
                    const priorityOrder = { low: 1, medium: 2, high: 3, urgent: 4 };
                    aValue = priorityOrder[aValue];
                    bValue = priorityOrder[bValue];
                }
                
                // Handle date sorting
                if (this.sortBy === 'due_date' || this.sortBy === 'created_at') {
                    aValue = new Date(aValue);
                    bValue = new Date(bValue);
                }
                
                if (this.sortOrder === 'desc') {
                    return bValue > aValue ? 1 : -1;
                } else {
                    return aValue > bValue ? 1 : -1;
                }
            });
            
            return filtered;
        },
        
        // Initialization
        async init() {
            console.log('Initializing Task Manager...');
            
            // Load initial data
            await this.loadTasks();
            await this.loadAssignees();
            
            // Set up real-time updates
            this.setupRealTimeUpdates();
            
            // Initialize drag and drop
            this.initializeDragAndDrop();
            
            console.log('Task Manager initialized successfully');
        },
        
        // Data Loading
        async loadTasks() {
            try {
                // Simulate API call - replace with actual endpoint
                this.tasks = await this.mockApiCall('/api/admin/staff/tasks', [
                    {
                        id: 1,
                        title: 'Clean kitchen equipment',
                        description: 'Deep clean all kitchen equipment including ovens, grills, and prep stations',
                        assignee_id: 1,
                        assignee: 'John Smith',
                        assignee_avatar: '/images/avatars/john.jpg',
                        priority: 'high',
                        category: 'kitchen',
                        status: 'todo',
                        due_date: '2024-01-15 14:00',
                        estimated_hours: 3,
                        progress: 0,
                        is_overdue: false,
                        is_recurring: false,
                        created_at: '2024-01-10 09:00'
                    },
                    {
                        id: 2,
                        title: 'Update menu displays',
                        description: 'Replace old menu boards with new seasonal offerings',
                        assignee_id: 2,
                        assignee: 'Sarah Johnson',
                        assignee_avatar: '/images/avatars/sarah.jpg',
                        priority: 'medium',
                        category: 'service',
                        status: 'in_progress',
                        due_date: '2024-01-16 10:00',
                        estimated_hours: 2,
                        progress: 65,
                        is_overdue: false,
                        is_recurring: false,
                        created_at: '2024-01-09 11:30'
                    },
                    {
                        id: 3,
                        title: 'Inventory count - dry goods',
                        description: 'Complete monthly inventory count for all dry goods and pantry items',
                        assignee_id: 3,
                        assignee: 'Mike Wilson',
                        assignee_avatar: '/images/avatars/mike.jpg',
                        priority: 'urgent',
                        category: 'admin',
                        status: 'review',
                        due_date: '2024-01-14 17:00',
                        estimated_hours: 4,
                        progress: 90,
                        is_overdue: true,
                        is_recurring: true,
                        created_at: '2024-01-08 08:15'
                    },
                    {
                        id: 4,
                        title: 'Staff training - food safety',
                        description: 'Conduct quarterly food safety training for all kitchen staff',
                        assignee_id: 1,
                        assignee: 'John Smith',
                        assignee_avatar: '/images/avatars/john.jpg',
                        priority: 'high',
                        category: 'admin',
                        status: 'completed',
                        due_date: '2024-01-12 15:00',
                        estimated_hours: 2.5,
                        progress: 100,
                        is_overdue: false,
                        is_recurring: true,
                        created_at: '2024-01-07 14:20'
                    }
                ]);
                
                // Update recent tasks
                this.recentTasks = this.tasks
                    .sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
                    .slice(0, 5);
                    
            } catch (error) {
                console.error('Error loading tasks:', error);
                this.showNotification('Error loading tasks', 'error');
            }
        },
        
        async loadAssignees() {
            try {
                // Simulate API call - replace with actual endpoint
                this.assignees = await this.mockApiCall('/api/admin/staff/assignees', [
                    { id: 1, name: 'John Smith', avatar: '/images/avatars/john.jpg' },
                    { id: 2, name: 'Sarah Johnson', avatar: '/images/avatars/sarah.jpg' },
                    { id: 3, name: 'Mike Wilson', avatar: '/images/avatars/mike.jpg' },
                    { id: 4, name: 'Lisa Brown', avatar: '/images/avatars/lisa.jpg' },
                    { id: 5, name: 'David Lee', avatar: '/images/avatars/david.jpg' }
                ]);
            } catch (error) {
                console.error('Error loading assignees:', error);
            }
        },
        
        // Mock API call for development
        async mockApiCall(endpoint, mockData) {
            return new Promise(resolve => {
                setTimeout(() => resolve(mockData), 300);
            });
        },
        
        // View Management
        setView(view) {
            this.currentView = view;
            console.log(`Switched to ${view} view`);
        },
        
        // Task Modal Management
        openTaskModal(priority = null) {
            this.resetTaskForm();
            if (priority) {
                this.taskForm.priority = priority;
            }
            this.editingTask = null;
            this.showTaskModal = true;
        },
        
        closeTaskModal() {
            this.showTaskModal = false;
            this.editingTask = null;
            this.resetTaskForm();
        },
        
        resetTaskForm() {
            this.taskForm = {
                id: null,
                title: '',
                description: '',
                assignee_id: '',
                priority: 'medium',
                category: 'kitchen',
                due_date: '',
                estimated_hours: '',
                status: 'todo',
                is_recurring: false,
                send_notifications: true,
                recurrence_pattern: 'weekly',
                dependencies: [],
                attachments: []
            };
        },
        
        // Task CRUD Operations
        async saveTask() {
            try {
                // Validate form
                if (!this.taskForm.title || !this.taskForm.assignee_id) {
                    this.showNotification('Please fill in all required fields', 'error');
                    return;
                }
                
                const taskData = { ...this.taskForm };
                
                if (this.editingTask) {
                    // Update existing task
                    await this.updateTask(taskData);
                } else {
                    // Create new task
                    await this.createTask(taskData);
                }
                
                this.closeTaskModal();
                await this.loadTasks(); // Refresh task list
                
            } catch (error) {
                console.error('Error saving task:', error);
                this.showNotification('Error saving task', 'error');
            }
        },
        
        async createTask(taskData) {
            // Simulate API call
            const newTask = {
                ...taskData,
                id: Date.now(), // Mock ID
                assignee: this.assignees.find(a => a.id == taskData.assignee_id)?.name || 'Unknown',
                assignee_avatar: this.assignees.find(a => a.id == taskData.assignee_id)?.avatar || '/images/default-avatar.jpg',
                progress: 0,
                is_overdue: false,
                created_at: new Date().toISOString()
            };
            
            this.tasks.unshift(newTask);
            this.showNotification('Task created successfully', 'success');
        },
        
        async updateTask(taskData) {
            // Simulate API call
            const index = this.tasks.findIndex(t => t.id === taskData.id);
            if (index !== -1) {
                this.tasks[index] = {
                    ...this.tasks[index],
                    ...taskData,
                    assignee: this.assignees.find(a => a.id == taskData.assignee_id)?.name || 'Unknown',
                    assignee_avatar: this.assignees.find(a => a.id == taskData.assignee_id)?.avatar || '/images/default-avatar.jpg'
                };
                this.showNotification('Task updated successfully', 'success');
            }
        },
        
        editTask(task) {
            this.editingTask = task;
            this.taskForm = { ...task };
            this.showTaskModal = true;
        },
        
        viewTask(task) {
            // Open task detail view or modal
            console.log('Viewing task:', task);
            // Implement task detail view
        },
        
        async deleteTask(task) {
            if (confirm('Are you sure you want to delete this task?')) {
                try {
                    // Simulate API call
                    this.tasks = this.tasks.filter(t => t.id !== task.id);
                    this.showNotification('Task deleted successfully', 'success');
                } catch (error) {
                    console.error('Error deleting task:', error);
                    this.showNotification('Error deleting task', 'error');
                }
            }
        },
        
        // Filtering and Search
        applyFilters() {
            console.log('Applying filters:', this.filters);
            // Filters are applied via computed property
        },
        
        clearFilters() {
            this.filters = {
                assignee: '',
                priority: '',
                category: '',
                status: ''
            };
            this.searchQuery = '';
        },
        
        searchTasks() {
            console.log('Searching tasks:', this.searchQuery);
            // Search is applied via computed property
        },
        
        sortTasks() {
            console.log('Sorting tasks by:', this.sortBy, this.sortOrder);
            // Sorting is applied via computed property
        },
        
        toggleSortOrder() {
            this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
        },
        
        // Kanban Specific Methods
        getTasksByStatus(status) {
            return this.filteredTasks.filter(task => task.status === status);
        },
        
        getTaskCount(status) {
            return this.getTasksByStatus(status).length;
        },
        
        // Drag and Drop
        initializeDragAndDrop() {
            // Drag and drop is handled by Alpine.js directives in the template
            console.log('Drag and drop initialized');
        },
        
        handleDragStart(event, task) {
            event.dataTransfer.setData('text/plain', JSON.stringify(task));
            event.dataTransfer.effectAllowed = 'move';
            console.log('Drag started for task:', task.title);
        },
        
        async handleDrop(event, newStatus) {
            event.preventDefault();
            
            try {
                const taskData = JSON.parse(event.dataTransfer.getData('text/plain'));
                const task = this.tasks.find(t => t.id === taskData.id);
                
                if (task && task.status !== newStatus) {
                    // Update task status
                    task.status = newStatus;
                    
                    // Update progress based on status
                    if (newStatus === 'completed') {
                        task.progress = 100;
                    } else if (newStatus === 'in_progress' && task.progress === 0) {
                        task.progress = 25;
                    }
                    
                    // Simulate API call to update task
                    await this.updateTaskStatus(task.id, newStatus);
                    
                    this.showNotification(`Task moved to ${newStatus.replace('_', ' ')}`, 'success');
                    console.log('Task dropped:', task.title, 'New status:', newStatus);
                }
            } catch (error) {
                console.error('Error handling drop:', error);
                this.showNotification('Error updating task status', 'error');
            }
        },
        
        async updateTaskStatus(taskId, status) {
            // Simulate API call
            console.log(`Updating task ${taskId} status to ${status}`);
        },
        
        // Selection Management
        toggleTaskSelection(taskId) {
            const index = this.selectedTasks.indexOf(taskId);
            if (index > -1) {
                this.selectedTasks.splice(index, 1);
            } else {
                this.selectedTasks.push(taskId);
            }
        },
        
        toggleSelectAll() {
            if (this.selectedTasks.length === this.filteredTasks.length) {
                this.selectedTasks = [];
            } else {
                this.selectedTasks = this.filteredTasks.map(task => task.id);
            }
        },
        
        // Bulk Operations
        async bulkUpdateStatus(status) {
            if (this.selectedTasks.length === 0) return;
            
            try {
                // Update selected tasks
                this.tasks.forEach(task => {
                    if (this.selectedTasks.includes(task.id)) {
                        task.status = status;
                        if (status === 'completed') {
                            task.progress = 100;
                        }
                    }
                });
                
                this.showNotification(`${this.selectedTasks.length} tasks updated`, 'success');
                this.selectedTasks = [];
            } catch (error) {
                console.error('Error in bulk update:', error);
                this.showNotification('Error updating tasks', 'error');
            }
        },
        
        async bulkUpdatePriority(priority) {
            if (this.selectedTasks.length === 0) return;
            
            try {
                this.tasks.forEach(task => {
                    if (this.selectedTasks.includes(task.id)) {
                        task.priority = priority;
                    }
                });
                
                this.showNotification(`${this.selectedTasks.length} tasks priority updated`, 'success');
                this.selectedTasks = [];
            } catch (error) {
                console.error('Error in bulk priority update:', error);
                this.showNotification('Error updating task priorities', 'error');
            }
        },
        
        bulkAssignTasks() {
            if (this.selectedTasks.length === 0) return;
            
            // Open bulk assign modal
            console.log('Opening bulk assign modal for tasks:', this.selectedTasks);
            // Implement bulk assign modal
        },
        
        async bulkDeleteTasks() {
            if (this.selectedTasks.length === 0) return;
            
            if (confirm(`Are you sure you want to delete ${this.selectedTasks.length} tasks?`)) {
                try {
                    this.tasks = this.tasks.filter(task => !this.selectedTasks.includes(task.id));
                    this.showNotification(`${this.selectedTasks.length} tasks deleted`, 'success');
                    this.selectedTasks = [];
                } catch (error) {
                    console.error('Error in bulk delete:', error);
                    this.showNotification('Error deleting tasks', 'error');
                }
            }
        },
        
        // Quick Actions
        openBulkAssign() {
            console.log('Opening bulk assign modal');
            // Implement bulk assign functionality
        },
        
        openTemplates() {
            console.log('Opening task templates');
            // Implement task templates functionality
        },
        
        // File Handling
        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            this.taskForm.attachments = [...(this.taskForm.attachments || []), ...files];
        },
        
        handleFileDrop(event) {
            const files = Array.from(event.dataTransfer.files);
            this.taskForm.attachments = [...(this.taskForm.attachments || []), ...files];
        },
        
        removeFile(index) {
            this.taskForm.attachments.splice(index, 1);
        },
        
        // Real-time Updates
        setupRealTimeUpdates() {
            // Set up WebSocket or polling for real-time updates
            console.log('Setting up real-time updates');
            
            // Example: Poll for updates every 30 seconds
            setInterval(() => {
                this.checkForUpdates();
            }, 30000);
        },
        
        async checkForUpdates() {
            try {
                // Check for task updates from server
                console.log('Checking for task updates...');
                // Implement real-time update checking
            } catch (error) {
                console.error('Error checking for updates:', error);
            }
        },
        
        // Notifications
        showNotification(message, type = 'info') {
            // Create and show notification
            const notification = document.createElement('div');
            notification.className = `notification notification--${type}`;
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
                zIndex: '1000',
                transform: 'translateX(100%)',
                transition: 'transform 0.3s ease'
            });
            
            // Set background color based on type
            const colors = {
                success: '#10b981',
                error: '#ef4444',
                warning: '#f59e0b',
                info: '#3b82f6'
            };
            notification.style.backgroundColor = colors[type] || colors.info;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 100);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        },
        
        // Utility Methods
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
        },
        
        formatDateTime(dateString) {
            return new Date(dateString).toLocaleString();
        },
        
        getPriorityColor(priority) {
            const colors = {
                low: '#10b981',
                medium: '#3b82f6',
                high: '#f59e0b',
                urgent: '#ef4444'
            };
            return colors[priority] || colors.medium;
        },
        
        getStatusColor(status) {
            const colors = {
                todo: '#6b7280',
                in_progress: '#3b82f6',
                review: '#f59e0b',
                completed: '#10b981'
            };
            return colors[status] || colors.todo;
        }
    };
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Staff Tasks JavaScript loaded');
    
    // Add any global event listeners or initialization here
    
    // Handle keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + N: New task
        if ((event.ctrlKey || event.metaKey) && event.key === 'n') {
            event.preventDefault();
            // Trigger new task modal if task manager is active
            const taskContainer = document.querySelector('.tasks-container');
            if (taskContainer) {
                // Dispatch custom event to open task modal
                taskContainer.dispatchEvent(new CustomEvent('open-task-modal'));
            }
        }
        
        // Escape: Close modals
        if (event.key === 'Escape') {
            // Close any open modals
            const modals = document.querySelectorAll('.modal-overlay');
            modals.forEach(modal => {
                if (modal.style.display !== 'none') {
                    modal.dispatchEvent(new CustomEvent('close-modal'));
                }
            });
        }
    });
    
    // Handle window resize for responsive adjustments
    window.addEventListener('resize', function() {
        // Adjust kanban board layout if needed
        const kanbanBoard = document.querySelector('.kanban-board');
        if (kanbanBoard) {
            // Add any responsive adjustments here
        }
    });
});

// Export for use in other modules if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { taskManager };
}
