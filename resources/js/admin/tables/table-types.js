/**
 * Table Types JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles table type creation, editing, and management with shapes and capacity
 */

class TableTypesManager {
    constructor() {
        this.types = [];
        this.filteredTypes = [];
        this.searchTerm = '';
        this.filters = {
            shape: '',
            capacity: ''
        };
        this.currentType = null;
        this.isEditing = false;
        this.selectedShape = 'rectangle';
        
        this.init();
    }

    /**
     * Initialize the types manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderTypes();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Shape selector events
        this.bindShapeEvents();
    }

    /**
     * Bind search and filter events
     */
    bindSearchEvents() {
        const typesSearch = document.getElementById('types-search');
        const shapeFilter = document.getElementById('shape-filter');
        const capacityFilter = document.getElementById('capacity-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (typesSearch) {
            typesSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderTypes();
            });
        }

        if (shapeFilter) {
            shapeFilter.addEventListener('change', (e) => {
                this.filters.shape = e.target.value;
                this.filterAndRenderTypes();
            });
        }

        if (capacityFilter) {
            capacityFilter.addEventListener('change', (e) => {
                this.filters.capacity = e.target.value;
                this.filterAndRenderTypes();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Type modal
        this.bindModalCloseEvents('type-modal', () => this.closeTypeModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeTypeModal();
            }
        });
    }

    /**
     * Bind modal close events for a specific modal
     */
    bindModalCloseEvents(modalId, closeCallback) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.cancel-type-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add type button
        document.querySelectorAll('.add-type-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openTypeModal());
        });

        // Export types button
        const exportBtn = document.querySelector('.export-types-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportTypes());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Type card click (for viewing details)
            if (e.target.closest('.type-card') && !e.target.closest('.type-action-btn')) {
                const typeId = parseInt(e.target.closest('.type-card').dataset.typeId);
                this.viewTypeDetails(typeId);
            }
            
            // Type action buttons
            if (e.target.closest('.type-action-btn')) {
                e.stopPropagation();
                const action = e.target.closest('.type-action-btn').dataset.action;
                const typeId = parseInt(e.target.closest('.type-card').dataset.typeId);
                
                if (action === 'edit') {
                    this.editType(typeId);
                } else if (action === 'delete') {
                    this.deleteType(typeId);
                }
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const typeForm = document.getElementById('type-form');
        if (typeForm) {
            typeForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveType();
            });
        }

        // Auto-generate type code from type name
        const typeNameInput = document.getElementById('type-name');
        const typeCodeInput = document.getElementById('type-code');
        
        if (typeNameInput && typeCodeInput) {
            typeNameInput.addEventListener('input', (e) => {
                if (!this.isEditing && !typeCodeInput.value) {
                    const code = this.generateTypeCode(e.target.value);
                    typeCodeInput.value = code;
                }
            });
        }

        // Validate capacity range
        const minCapacityInput = document.getElementById('min-capacity');
        const maxCapacityInput = document.getElementById('max-capacity');
        
        if (minCapacityInput && maxCapacityInput) {
            minCapacityInput.addEventListener('change', () => {
                const min = parseInt(minCapacityInput.value);
                const max = parseInt(maxCapacityInput.value);
                if (min > max) {
                    maxCapacityInput.value = min;
                }
            });
            
            maxCapacityInput.addEventListener('change', () => {
                const min = parseInt(minCapacityInput.value);
                const max = parseInt(maxCapacityInput.value);
                if (max < min) {
                    minCapacityInput.value = max;
                }
            });
        }
    }

    /**
     * Bind shape selector events
     */
    bindShapeEvents() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.shape-option')) {
                const shapeOption = e.target.closest('.shape-option');
                const shape = shapeOption.dataset.shape;
                this.selectShape(shape);
            }
        });
    }

    /**
     * Select shape
     */
    selectShape(shape) {
        this.selectedShape = shape;
        document.getElementById('selected-shape').value = shape;
        
        // Update visual selection
        document.querySelectorAll('.shape-option').forEach(option => {
            option.classList.remove('active');
        });
        document.querySelector(`[data-shape="${shape}"]`).classList.add('active');
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.types = [
            {
                id: 1,
                name: '2-Seater Table',
                code: 'T2',
                shape: 'rectangle',
                minCapacity: 2,
                maxCapacity: 2,
                description: 'Small rectangular table perfect for couples',
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 2,
                name: '4-Seater Table',
                code: 'T4',
                shape: 'rectangle',
                minCapacity: 3,
                maxCapacity: 4,
                description: 'Standard rectangular table for small groups',
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 3,
                name: '6-Seater Round Table',
                code: 'R6',
                shape: 'circle',
                minCapacity: 5,
                maxCapacity: 6,
                description: 'Round table ideal for family dining and conversations',
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 4,
                name: '8-Seater Round Table',
                code: 'R8',
                shape: 'circle',
                minCapacity: 7,
                maxCapacity: 8,
                description: 'Large round table for bigger groups and celebrations',
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 5,
                name: 'Bar Stool',
                code: 'BS',
                shape: 'circle',
                minCapacity: 1,
                maxCapacity: 1,
                description: 'Individual bar seating for casual dining',
                createdAt: new Date('2024-01-17'),
                updatedAt: new Date('2024-01-17')
            },
            {
                id: 6,
                name: 'Booth Table',
                code: 'BTH',
                shape: 'rectangle',
                minCapacity: 2,
                maxCapacity: 6,
                description: 'Comfortable booth seating with flexible capacity',
                createdAt: new Date('2024-01-17'),
                updatedAt: new Date('2024-01-17')
            }
        ];
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalTypes = this.types.length;
        const uniqueShapes = new Set(this.types.map(t => t.shape)).size;
        const maxCapacity = this.types.length > 0 
            ? Math.max(...this.types.map(t => t.maxCapacity))
            : 0;

        document.getElementById('total-types').textContent = totalTypes;
        document.getElementById('unique-shapes').textContent = uniqueShapes;
        document.getElementById('max-capacity').textContent = maxCapacity;
    }

    /**
     * Filter and render types
     */
    filterAndRenderTypes() {
        this.filteredTypes = this.types.filter(type => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                type.name.toLowerCase().includes(this.searchTerm) ||
                type.code.toLowerCase().includes(this.searchTerm) ||
                type.description.toLowerCase().includes(this.searchTerm);

            // Shape filter
            const shapeMatch = !this.filters.shape || type.shape === this.filters.shape;

            // Capacity filter
            let capacityMatch = true;
            if (this.filters.capacity) {
                const maxCap = type.maxCapacity;
                switch (this.filters.capacity) {
                    case 'small':
                        capacityMatch = maxCap <= 2;
                        break;
                    case 'medium':
                        capacityMatch = maxCap >= 3 && maxCap <= 4;
                        break;
                    case 'large':
                        capacityMatch = maxCap >= 5 && maxCap <= 8;
                        break;
                    case 'xlarge':
                        capacityMatch = maxCap >= 9;
                        break;
                }
            }

            return searchMatch && shapeMatch && capacityMatch;
        });

        this.renderTypes();
    }

    /**
     * Render types
     */
    renderTypes() {
        const typesGrid = document.getElementById('types-grid');
        if (!typesGrid) return;

        const typesToShow = this.filteredTypes.length ? this.filteredTypes : this.types;

        if (typesToShow.length === 0) {
            typesGrid.innerHTML = `
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h3>No table types found</h3>
                    <p>No table types match your current search and filter criteria.</p>
                </div>
            `;
            return;
        }

        typesGrid.innerHTML = typesToShow.map(type => `
            <div class="type-card" data-type-id="${type.id}" data-shape="${type.shape}">
                <div class="type-header">
                    <div class="type-shape-display">
                        <div class="shape-preview ${type.shape}"></div>
                    </div>
                    <div class="type-info">
                        <div class="type-name">${type.name}</div>
                        <div class="type-code">${type.code}</div>
                    </div>
                </div>
                
                <div class="type-details">
                    <div class="type-detail">
                        <span class="detail-label">Shape</span>
                        <span class="detail-value">${this.formatShape(type.shape)}</span>
                    </div>
                    <div class="type-detail">
                        <span class="detail-label">Capacity</span>
                        <span class="detail-value capacity-range">${type.minCapacity === type.maxCapacity ? type.minCapacity : type.minCapacity + '-' + type.maxCapacity} people</span>
                    </div>
                    <div class="type-detail">
                        <span class="detail-label">Created</span>
                        <span class="detail-value">${this.formatDate(type.createdAt)}</span>
                    </div>
                </div>
                
                ${type.description ? `<div class="type-description">${type.description}</div>` : ''}
                
                <div class="type-actions">
                    <button class="type-action-btn edit" data-action="edit" title="Edit Type">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="type-action-btn delete" data-action="delete" title="Delete Type">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Open type modal
     */
    openTypeModal(type = null) {
        this.currentType = type;
        this.isEditing = !!type;
        
        const modal = document.getElementById('type-modal');
        const title = document.getElementById('type-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Table Type' : 'Add Table Type';
            
            if (this.isEditing) {
                this.populateTypeForm(type);
            } else {
                this.resetTypeForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close type modal
     */
    closeTypeModal() {
        const modal = document.getElementById('type-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetTypeForm();
            this.currentType = null;
            this.isEditing = false;
        }
    }

    /**
     * Populate type form
     */
    populateTypeForm(type) {
        document.getElementById('type-name').value = type.name;
        document.getElementById('type-code').value = type.code;
        document.getElementById('min-capacity').value = type.minCapacity;
        document.getElementById('max-capacity').value = type.maxCapacity;
        document.getElementById('type-description').value = type.description || '';
        
        this.selectShape(type.shape);
    }

    /**
     * Reset type form
     */
    resetTypeForm() {
        const form = document.getElementById('type-form');
        if (form) {
            form.reset();
            document.getElementById('min-capacity').value = '2';
            document.getElementById('max-capacity').value = '4';
        }
        
        this.selectShape('rectangle');
    }

    /**
     * Save type
     */
    saveType() {
        const formData = new FormData(document.getElementById('type-form'));
        
        const typeData = {
            name: formData.get('type_name'),
            code: formData.get('type_code').toUpperCase(),
            shape: this.selectedShape,
            minCapacity: parseInt(formData.get('min_capacity')),
            maxCapacity: parseInt(formData.get('max_capacity')),
            description: formData.get('description') || ''
        };

        // Validate required fields
        if (!typeData.name || !typeData.code || !typeData.minCapacity || !typeData.maxCapacity) {
            this.showNotification('Please fill in all required fields', 'error');
            return;
        }

        // Validate capacity range
        if (typeData.minCapacity > typeData.maxCapacity) {
            this.showNotification('Minimum capacity cannot be greater than maximum capacity', 'error');
            return;
        }

        // Check for duplicate type code
        const duplicateCode = this.types.find(t => 
            t.code === typeData.code && (!this.isEditing || t.id !== this.currentType.id)
        );
        
        if (duplicateCode) {
            this.showNotification('Type code already exists. Please use a different code.', 'error');
            return;
        }

        if (this.isEditing) {
            // Update existing type
            const index = this.types.findIndex(t => t.id === this.currentType.id);
            if (index !== -1) {
                this.types[index] = { 
                    ...this.types[index], 
                    ...typeData, 
                    updatedAt: new Date() 
                };
                this.showNotification('Table type updated successfully', 'success');
            }
        } else {
            // Add new type
            const newType = {
                id: Math.max(...this.types.map(t => t.id)) + 1,
                ...typeData,
                createdAt: new Date(),
                updatedAt: new Date()
            };
            this.types.push(newType);
            this.showNotification('Table type added successfully', 'success');
        }

        this.updateStatistics();
        this.filterAndRenderTypes();
        this.closeTypeModal();
    }

    /**
     * Edit type
     */
    editType(typeId) {
        const type = this.types.find(t => t.id === typeId);
        if (type) {
            this.openTypeModal(type);
        }
    }

    /**
     * Delete type
     */
    deleteType(typeId) {
        const type = this.types.find(t => t.id === typeId);
        if (type && confirm(`Are you sure you want to delete "${type.name}"?`)) {
            this.types = this.types.filter(t => t.id !== typeId);
            this.updateStatistics();
            this.filterAndRenderTypes();
            this.showNotification('Table type deleted successfully', 'success');
        }
    }

    /**
     * View type details
     */
    viewTypeDetails(typeId) {
        const type = this.types.find(t => t.id === typeId);
        if (type) {
            // For now, just edit the type
            this.editType(typeId);
        }
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            shape: '',
            capacity: ''
        };
        
        // Reset form inputs
        const typesSearch = document.getElementById('types-search');
        const shapeFilter = document.getElementById('shape-filter');
        const capacityFilter = document.getElementById('capacity-filter');
        
        if (typesSearch) typesSearch.value = '';
        if (shapeFilter) shapeFilter.value = '';
        if (capacityFilter) capacityFilter.value = '';
        
        this.filterAndRenderTypes();
    }

    /**
     * Export types
     */
    exportTypes() {
        const csvContent = this.generateTypesCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `table-types-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Table types exported successfully', 'success');
    }

    /**
     * Generate types CSV
     */
    generateTypesCSV() {
        const headers = ['ID', 'Name', 'Code', 'Shape', 'Min Capacity', 'Max Capacity', 'Description', 'Created'];
        
        const rows = this.types.map(type => [
            type.id,
            type.name,
            type.code,
            this.formatShape(type.shape),
            type.minCapacity,
            type.maxCapacity,
            type.description || '',
            type.createdAt.toISOString().split('T')[0]
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }

    /**
     * Generate type code from name
     */
    generateTypeCode(name) {
        if (!name) return '';
        
        // Extract meaningful characters
        const words = name.trim().split(/\s+/);
        let code = '';
        
        for (const word of words) {
            if (code.length >= 5) break;
            const chars = word.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
            if (chars.length > 0) {
                code += chars.substring(0, Math.min(2, 5 - code.length));
            }
        }
        
        return code.substring(0, 5);
    }

    /**
     * Utility methods
     */
    formatShape(shape) {
        const shapeMap = {
            rectangle: 'Rectangle',
            circle: 'Circle',
            square: 'Square',
            oval: 'Oval'
        };
        return shapeMap[shape] || shape;
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('en-GB', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close">&times;</button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
        
        // Manual close
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            });
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.tableTypesManager = new TableTypesManager();
});
