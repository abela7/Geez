/**
 * Templates JavaScript
 * Handles template management, filtering, and CRUD operations
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize templates functionality
    initializeTemplates();
});

function initializeTemplates() {
    // Initialize Alpine.js data
    window.templatesData = function() {
        return {
            // Filter state
            filters: {
                category: 'all',
                role: 'all_roles',
                recurring_type: 'all',
                search: ''
            },
            
            // Templates data (will be populated from server)
            templates: [],
            
            // Filtered templates
            filteredTemplates: [],
            
            // Initialize
            init() {
                this.loadTemplatesData();
                this.applyFilters();
            },
            
            // Load templates data from server
            loadTemplatesData() {
                // In a real implementation, this would fetch from the server
                // For now, we'll use the data from the Blade template
                this.templates = window.templatesData || [];
                this.filteredTemplates = [...this.templates];
            },
            
            // Apply filters to templates list
            applyFilters() {
                this.filteredTemplates = this.templates.filter(template => {
                    // Category filter
                    if (this.filters.category !== 'all' && template.category !== this.filters.category) {
                        return false;
                    }
                    
                    // Role filter
                    if (this.filters.role !== 'all_roles' && template.assigned_role !== this.filters.role) {
                        return false;
                    }
                    
                    // Recurring type filter
                    if (this.filters.recurring_type !== 'all' && template.recurring_type !== this.filters.recurring_type) {
                        return false;
                    }
                    
                    // Search filter
                    if (this.filters.search) {
                        const searchTerm = this.filters.search.toLowerCase();
                        const searchableText = `${template.name} ${template.description} ${template.tags.join(' ')}`.toLowerCase();
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
                    category: 'all',
                    role: 'all_roles',
                    recurring_type: 'all',
                    search: ''
                };
                this.applyFilters();
            },
            
            // Check if template should be visible based on filters
            isTemplateVisible(template) {
                return this.filteredTemplates.some(filteredTemplate => filteredTemplate.id === template.id);
            },
            
            // Toggle template active status
            toggleTemplateStatus(templateId) {
                this.showNotification('Updating template status...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    // Update local data
                    const template = this.templates.find(t => t.id === templateId);
                    if (template) {
                        template.is_active = !template.is_active;
                        this.applyFilters();
                        this.showNotification(
                            `Template ${template.is_active ? 'activated' : 'deactivated'} successfully!`, 
                            'success'
                        );
                    }
                }, 500);
            },
            
            // Duplicate template
            duplicateTemplate(templateId) {
                if (!confirm('Are you sure you want to duplicate this template?')) {
                    return;
                }
                
                this.showNotification('Duplicating template...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    const originalTemplate = this.templates.find(t => t.id === templateId);
                    if (originalTemplate) {
                        const duplicatedTemplate = {
                            ...originalTemplate,
                            id: Date.now(),
                            name: `Copy of ${originalTemplate.name}`,
                            is_active: false,
                            usage_count: 0,
                            completion_rate: 0,
                            created_at: new Date().toISOString(),
                            updated_at: new Date().toISOString()
                        };
                        
                        this.templates.unshift(duplicatedTemplate);
                        this.applyFilters();
                        this.showNotification('Template duplicated successfully!', 'success');
                    }
                }, 1000);
            },
            
            // Delete template
            deleteTemplate(templateId) {
                if (!confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
                    return;
                }
                
                this.showNotification('Deleting template...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    const templateIndex = this.templates.findIndex(t => t.id === templateId);
                    if (templateIndex !== -1) {
                        this.templates.splice(templateIndex, 1);
                        this.applyFilters();
                        this.showNotification('Template deleted successfully!', 'success');
                    }
                }, 1000);
            },
            
            // Refresh templates
            refreshTemplates() {
                this.showNotification('Refreshing templates...', 'info');
                
                // Simulate API call
                setTimeout(() => {
                    this.loadTemplatesData();
                    this.applyFilters();
                    this.showNotification('Templates refreshed successfully!', 'success');
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

// Template form handling
function initializeTemplateForm() {
    return {
        // Form data
        form: {
            name: '',
            description: '',
            category: 'opening_tasks',
            assigned_role: 'all_roles',
            recurring_type: 'daily',
            estimated_duration: 30,
            priority: 'normal',
            instructions: [''],
            tags: [''],
            is_active: true
        },
        
        // Form state
        isSubmitting: false,
        errors: {},
        
        // Add new instruction
        addInstruction() {
            this.form.instructions.push('');
        },
        
        // Remove instruction
        removeInstruction(index) {
            if (this.form.instructions.length > 1) {
                this.form.instructions.splice(index, 1);
            }
        },
        
        // Add new tag
        addTag() {
            this.form.tags.push('');
        },
        
        // Remove tag
        removeTag(index) {
            if (this.form.tags.length > 1) {
                this.form.tags.splice(index, 1);
            }
        },
        
        // Submit form
        submitForm() {
            if (this.isSubmitting) return;
            
            this.isSubmitting = true;
            this.errors = {};
            
            // Filter out empty instructions and tags
            this.form.instructions = this.form.instructions.filter(instruction => instruction.trim() !== '');
            this.form.tags = this.form.tags.filter(tag => tag.trim() !== '');
            
            // Simulate form submission
            setTimeout(() => {
                // In real implementation, this would submit to the server
                console.log('Form submitted:', this.form);
                
                // Redirect to templates list
                window.location.href = '/admin/todos/templates';
            }, 1000);
        },
        
        // Cancel form
        cancelForm() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '/admin/todos/templates';
            }
        }
    };
}

// Export functions for global access
window.templatesData = window.templatesData || function() {
    return {
        filters: { category: 'all', role: 'all_roles', recurring_type: 'all', search: '' },
        templates: [],
        filteredTemplates: [],
        init() {},
        loadTemplatesData() {},
        applyFilters() {},
        clearFilters() {},
        isTemplateVisible() { return true; },
        toggleTemplateStatus() {},
        duplicateTemplate() {},
        deleteTemplate() {},
        refreshTemplates() {},
        showNotification() {}
    };
};

window.initializeTemplateForm = initializeTemplateForm;
