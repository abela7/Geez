// Shift Templates JavaScript

// Main Shifts Templates Data
function shiftsTemplatesData() {
    return {
        // Filters
        filterType: 'all',
        filterStatus: 'all',
        searchQuery: '',
        
        // Modal states
        showApplyTemplateModal: false,
        showImportModal: false,
        
        // Selection states
        selectedTemplate: null,
        applicationPreview: null,
        
        // Application settings
        applicationSettings: {
            startDate: '',
            endDate: '',
            overwriteExisting: false,
        },
        
        // Methods
        init() {
            this.applyFilters();
            this.setDefaultDates();
        },
        
        setDefaultDates() {
            const today = new Date();
            const nextWeek = new Date(today);
            nextWeek.setDate(today.getDate() + 7);
            const monthLater = new Date(today);
            monthLater.setMonth(today.getMonth() + 1);
            
            this.applicationSettings.startDate = nextWeek.toISOString().split('T')[0];
            this.applicationSettings.endDate = monthLater.toISOString().split('T')[0];
        },
        
        applyFilters() {
            const cards = document.querySelectorAll('.template-card');
            let visibleCount = 0;
            
            cards.forEach(card => {
                const type = card.dataset.type;
                const status = card.dataset.status;
                const name = card.dataset.name;
                
                let visible = true;
                
                // Type filter
                if (this.filterType !== 'all' && type !== this.filterType) {
                    visible = false;
                }
                
                // Status filter
                if (this.filterStatus !== 'all' && status !== this.filterStatus) {
                    visible = false;
                }
                
                // Search filter
                if (this.searchQuery) {
                    const query = this.searchQuery.toLowerCase();
                    if (!name.includes(query)) {
                        visible = false;
                    }
                }
                
                card.style.display = visible ? 'block' : 'none';
                if (visible) visibleCount++;
            });
            
            this.showFilterNotification(visibleCount, cards.length);
        },
        
        showApplyModal(template) {
            this.selectedTemplate = template;
            this.applicationPreview = null;
            this.showApplyTemplateModal = true;
        },
        
        async generatePreview() {
            if (!this.selectedTemplate || !this.applicationSettings.startDate || !this.applicationSettings.endDate) {
                this.showNotification('Please select dates first', 'warning');
                return;
            }
            
            try {
                const response = await fetch(`/admin/shifts/templates/${this.selectedTemplate.id}/preview`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        start_date: this.applicationSettings.startDate,
                        end_date: this.applicationSettings.endDate,
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.applicationPreview = data.preview;
                    this.showNotification('Preview generated successfully', 'success');
                } else {
                    this.showNotification('Failed to generate preview', 'error');
                }
            } catch (error) {
                console.error('Error generating preview:', error);
                this.showNotification('Error generating preview', 'error');
            }
        },
        
        async confirmApplication() {
            if (!this.selectedTemplate || !this.applicationSettings.startDate || !this.applicationSettings.endDate) {
                this.showNotification('Please fill in all required fields', 'warning');
                return;
            }
            
            try {
                const response = await fetch(`/admin/shifts/templates/${this.selectedTemplate.id}/apply`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    },
                    body: JSON.stringify({
                        start_date: this.applicationSettings.startDate,
                        end_date: this.applicationSettings.endDate,
                        overwrite_existing: this.applicationSettings.overwriteExisting,
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(`Template applied successfully! Created ${data.shifts_created} shifts.`, 'success');
                    this.showApplyTemplateModal = false;
                    this.updateTemplateUsage(this.selectedTemplate.id);
                } else {
                    this.showNotification(data.message || 'Failed to apply template', 'error');
                }
            } catch (error) {
                console.error('Error applying template:', error);
                this.showNotification('Error applying template', 'error');
            }
        },
        
        updateTemplateUsage(templateId) {
            // Update the usage count in the UI
            const templateCards = document.querySelectorAll(`[data-template-id="${templateId}"]`);
            templateCards.forEach(card => {
                const usageElement = card.querySelector('.usage-count, .stat-value');
                if (usageElement) {
                    const currentUsage = parseInt(usageElement.textContent) || 0;
                    usageElement.textContent = currentUsage + 1;
                }
            });
        },
        
        previewTemplate(template) {
            // In a real app, this would show a detailed preview modal
            this.showNotification(`Previewing template: ${template.name}`, 'info');
        },
        
        editTemplate(templateId) {
            window.location.href = `/admin/shifts/templates/${templateId}/edit`;
        },
        
        async duplicateTemplate(templateId) {
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}/duplicate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    // In a real app, you might refresh the page or add the new template to the list
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    this.showNotification(data.message || 'Failed to duplicate template', 'error');
                }
            } catch (error) {
                console.error('Error duplicating template:', error);
                this.showNotification('Error duplicating template', 'error');
            }
        },
        
        exportTemplate(templateId) {
            // In a real app, this would trigger a download
            this.showNotification('Template exported successfully', 'success');
        },
        
        async deleteTemplate(templateId) {
            if (!confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
                return;
            }
            
            try {
                const response = await fetch(`/admin/shifts/templates/${templateId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    this.showNotification('Template deleted successfully', 'success');
                    // Remove the template card from the UI
                    const templateCard = document.querySelector(`[data-template-id="${templateId}"]`);
                    if (templateCard) {
                        templateCard.remove();
                    }
                } else {
                    this.showNotification('Failed to delete template', 'error');
                }
            } catch (error) {
                console.error('Error deleting template:', error);
                this.showNotification('Error deleting template', 'error');
            }
        },
        
        formatCurrency(amount) {
            if (!amount) return '$0';
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        },
        
        showFilterNotification(visible, total) {
            if (visible < total) {
                this.showNotification(`Showing ${visible} of ${total} templates`, 'info');
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

// Template Import Logic
function templateImportData() {
    return {
        importFile: null,
        importProgress: 0,
        isImporting: false,
        
        handleFileSelect(event) {
            this.importFile = event.target.files[0];
        },
        
        async importTemplate() {
            if (!this.importFile) {
                this.showNotification('Please select a file to import', 'warning');
                return;
            }
            
            this.isImporting = true;
            this.importProgress = 0;
            
            try {
                // Simulate import progress
                const progressInterval = setInterval(() => {
                    this.importProgress += 10;
                    if (this.importProgress >= 100) {
                        clearInterval(progressInterval);
                    }
                }, 200);
                
                // Mock import delay
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                this.showNotification('Template imported successfully!', 'success');
                this.resetImport();
                
                // Refresh the page to show the new template
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
                
            } catch (error) {
                console.error('Error importing template:', error);
                this.showNotification('Error importing template', 'error');
            } finally {
                this.isImporting = false;
            }
        },
        
        resetImport() {
            this.importFile = null;
            this.importProgress = 0;
            this.isImporting = false;
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

// Template Creation Helper
function templateCreationHelper() {
    return {
        shifts: [],
        
        addShift() {
            this.shifts.push({
                id: Date.now(),
                name: '',
                department: '',
                start_time: '',
                end_time: '',
                required_staff: 1,
                days: [],
            });
        },
        
        removeShift(index) {
            this.shifts.splice(index, 1);
        },
        
        duplicateShift(index) {
            const shift = { ...this.shifts[index] };
            shift.id = Date.now();
            shift.name = shift.name + ' (Copy)';
            this.shifts.splice(index + 1, 0, shift);
        },
        
        calculateTotalStaff() {
            return this.shifts.reduce((total, shift) => total + (shift.required_staff || 0), 0);
        },
        
        calculateEstimatedCost() {
            // Mock calculation based on shifts
            const totalHours = this.shifts.reduce((total, shift) => {
                if (shift.start_time && shift.end_time) {
                    const start = new Date(`2000-01-01 ${shift.start_time}`);
                    let end = new Date(`2000-01-01 ${shift.end_time}`);
                    
                    if (end <= start) {
                        end.setDate(end.getDate() + 1);
                    }
                    
                    const hours = (end - start) / (1000 * 60 * 60);
                    return total + (hours * (shift.required_staff || 0) * shift.days.length);
                }
                return total;
            }, 0);
            
            return totalHours * 18; // $18/hour average
        },
        
        validateTemplate() {
            if (this.shifts.length === 0) {
                return 'Template must have at least one shift';
            }
            
            for (let shift of this.shifts) {
                if (!shift.name || !shift.department || !shift.start_time || !shift.end_time) {
                    return 'All shifts must have name, department, and times';
                }
                
                if (shift.days.length === 0) {
                    return 'All shifts must have at least one day selected';
                }
            }
            
            return null;
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

// Utility functions for template management
function formatTemplateDate(date) {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function calculateTemplateDuration(startTime, endTime) {
    const start = new Date(`2000-01-01 ${startTime}`);
    let end = new Date(`2000-01-01 ${endTime}`);
    
    if (end <= start) {
        end.setDate(end.getDate() + 1);
    }
    
    const hours = (end - start) / (1000 * 60 * 60);
    return hours;
}

function formatTemplateDuration(hours) {
    const wholeHours = Math.floor(hours);
    const minutes = Math.round((hours - wholeHours) * 60);
    
    if (minutes === 0) {
        return `${wholeHours}h`;
    } else {
        return `${wholeHours}h ${minutes}m`;
    }
}
