/**
 * Menu Modifiers Page JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles modifier group and modifier item management
 */

class MenuModifiersPage {
    constructor() {
        this.modifierGroups = [];
        this.filteredGroups = [];
        this.currentGroup = null;
        this.currentModifier = null;
        this.currentModifiers = [];
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the modifiers page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadModifierGroups();
        this.updateStatistics();
        this.setupTabs();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Group management events
        this.bindGroupEvents();
        
        // Filter and search events
        this.bindFilterEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Tab events
        this.bindTabEvents();
        
        // Modifier item events
        this.bindModifierEvents();
        
        // Import/Export events
        this.bindImportExportEvents();
    }

    /**
     * Bind group management events
     */
    bindGroupEvents() {
        // Add group buttons
        document.querySelectorAll('.add-modifier-group-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openGroupModal());
        });
    }

    /**
     * Bind filter and search events
     */
    bindFilterEvents() {
        // Search input
        const searchInput = document.getElementById('modifier-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.handleSearch(e.target.value));
        }

        // Filter selects
        const filters = ['type-filter', 'status-filter', 'sort-filter'];
        filters.forEach(filterId => {
            const filter = document.getElementById(filterId);
            if (filter) {
                filter.addEventListener('change', () => this.applyFilters());
            }
        });

        // Clear filters
        const clearBtn = document.querySelector('.clear-filters-btn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Group modal
        this.bindModalCloseEvents('modifier-group-modal', () => this.closeGroupModal());
        
        // Modifier item modal
        this.bindModalCloseEvents('modifier-item-modal', () => this.closeModifierModal());
        
        // Group details modal
        this.bindModalCloseEvents('group-details-modal', () => this.closeGroupDetailsModal());

        // Edit from details
        const editGroupBtn = document.querySelector('.edit-group-btn');
        if (editGroupBtn) {
            editGroupBtn.addEventListener('click', () => this.editFromDetails());
        }

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeGroupModal();
                this.closeModifierModal();
                this.closeGroupDetailsModal();
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
        const cancelBtn = modal.querySelector('.cancel-btn, .cancel-modifier-btn, .close-details-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        // Group form submission
        const groupForm = document.getElementById('modifier-group-form');
        if (groupForm) {
            groupForm.addEventListener('submit', (e) => this.handleGroupFormSubmit(e));
        }

        // Modifier form submission
        const modifierForm = document.getElementById('modifier-item-form');
        if (modifierForm) {
            modifierForm.addEventListener('submit', (e) => this.handleModifierFormSubmit(e));
        }

        // Selection type change
        const selectionType = document.getElementById('selection-type');
        if (selectionType) {
            selectionType.addEventListener('change', (e) => this.handleSelectionTypeChange(e.target.value));
        }
    }

    /**
     * Bind tab events
     */
    bindTabEvents() {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const tabName = btn.dataset.tab;
                this.switchTab(tabName);
            });
        });
    }

    /**
     * Bind modifier events
     */
    bindModifierEvents() {
        // Add modifier buttons
        document.querySelectorAll('.add-modifier-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openModifierModal());
        });
    }

    /**
     * Bind import/export events
     */
    bindImportExportEvents() {
        // Import modifiers
        const importBtn = document.querySelector('.import-modifiers-btn');
        if (importBtn) {
            importBtn.addEventListener('click', () => this.importModifiers());
        }

        // Export modifiers
        const exportBtn = document.querySelector('.export-modifiers-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportModifiers());
        }
    }

    /**
     * Setup tabs functionality
     */
    setupTabs() {
        this.switchTab('basic-info');
    }

    /**
     * Switch between tabs
     */
    switchTab(tabName) {
        // Update tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.tab === tabName);
        });

        // Update tab content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.toggle('active', content.dataset.tab === tabName);
        });
    }

    /**
     * Open group modal
     */
    openGroupModal(group = null) {
        const modal = document.getElementById('modifier-group-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('modifier-group-form');

        if (modal && title && form) {
            this.currentGroup = group;
            this.currentModifiers = group ? [...group.modifiers] : [];
            
            if (group) {
                title.textContent = 'Edit Modifier Group';
                this.populateGroupForm(group);
            } else {
                title.textContent = 'Add New Modifier Group';
                form.reset();
                this.currentModifiers = [];
            }

            this.renderModifiersList();
            this.updateDefaultSelectionOptions();
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first input
            const firstInput = form.querySelector('input, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    /**
     * Close group modal
     */
    closeGroupModal() {
        const modal = document.getElementById('modifier-group-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentGroup = null;
            this.currentModifiers = [];
        }
    }

    /**
     * Open modifier modal
     */
    openModifierModal(modifier = null) {
        const modal = document.getElementById('modifier-item-modal');
        const title = modal?.querySelector('.modal-title');
        const form = document.getElementById('modifier-item-form');

        if (modal && title && form) {
            this.currentModifier = modifier;
            
            if (modifier) {
                title.textContent = 'Edit Modifier';
                this.populateModifierForm(modifier);
            } else {
                title.textContent = 'Add New Modifier';
                form.reset();
                // Set default display order
                const orderInput = document.getElementById('modifier-display-order');
                if (orderInput) {
                    orderInput.value = this.currentModifiers.length;
                }
            }

            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
            
            // Focus first input
            const firstInput = form.querySelector('input');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }

    /**
     * Close modifier modal
     */
    closeModifierModal() {
        const modal = document.getElementById('modifier-item-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentModifier = null;
        }
    }

    /**
     * Open group details modal
     */
    openGroupDetailsModal(group) {
        const modal = document.getElementById('group-details-modal');
        const content = document.getElementById('group-details-content');

        if (modal && content && group) {
            this.currentGroup = group;
            this.populateGroupDetails(group);
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close group details modal
     */
    closeGroupDetailsModal() {
        const modal = document.getElementById('group-details-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentGroup = null;
        }
    }

    /**
     * Edit from details modal
     */
    editFromDetails() {
        if (this.currentGroup) {
            this.closeGroupDetailsModal();
            this.openGroupModal(this.currentGroup);
        }
    }

    /**
     * Handle search
     */
    handleSearch(query) {
        this.applyFilters();
    }

    /**
     * Apply filters
     */
    applyFilters() {
        const searchQuery = document.getElementById('modifier-search')?.value.toLowerCase() || '';
        const typeFilter = document.getElementById('type-filter')?.value || '';
        const statusFilter = document.getElementById('status-filter')?.value || '';
        const sortFilter = document.getElementById('sort-filter')?.value || 'name';

        // Filter groups
        this.filteredGroups = this.modifierGroups.filter(group => {
            // Search filter
            const matchesSearch = !searchQuery || 
                group.name.toLowerCase().includes(searchQuery) ||
                group.description.toLowerCase().includes(searchQuery);

            // Type filter
            const matchesType = !typeFilter || group.selection_type === typeFilter;

            // Status filter
            const matchesStatus = !statusFilter || 
                (statusFilter === 'active' && group.active) ||
                (statusFilter === 'inactive' && !group.active);

            return matchesSearch && matchesType && matchesStatus;
        });

        // Sort groups
        this.sortGroups(sortFilter);
        this.renderGroups();
    }

    /**
     * Sort groups
     */
    sortGroups(sortBy) {
        this.filteredGroups.sort((a, b) => {
            switch (sortBy) {
                case 'name':
                    return a.name.localeCompare(b.name);
                case 'type':
                    return a.selection_type.localeCompare(b.selection_type);
                case 'modifiers':
                    return (b.modifiers?.length || 0) - (a.modifiers?.length || 0);
                case 'created':
                    return new Date(b.created_at) - new Date(a.created_at);
                default:
                    return a.display_order - b.display_order;
            }
        });
    }

    /**
     * Clear filters
     */
    clearFilters() {
        document.getElementById('modifier-search').value = '';
        document.getElementById('type-filter').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('sort-filter').value = 'name';
        
        this.filteredGroups = [...this.modifierGroups];
        this.sortGroups('name');
        this.renderGroups();
    }

    /**
     * Handle selection type change
     */
    handleSelectionTypeChange(type) {
        const maxSelectionsInput = document.getElementById('max-selections');
        if (maxSelectionsInput) {
            if (type === 'single') {
                maxSelectionsInput.value = 1;
                maxSelectionsInput.disabled = true;
            } else {
                maxSelectionsInput.disabled = false;
                if (maxSelectionsInput.value === '1') {
                    maxSelectionsInput.value = '';
                }
            }
        }
    }

    /**
     * Handle group form submission
     */
    handleGroupFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateGroupForm()) {
            this.saveGroup();
        }
    }

    /**
     * Handle modifier form submission
     */
    handleModifierFormSubmit(e) {
        e.preventDefault();
        
        if (this.validateModifierForm()) {
            this.saveModifier();
        }
    }

    /**
     * Validate group form
     */
    validateGroupForm() {
        const form = document.getElementById('modifier-group-form');
        if (!form) return false;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    /**
     * Validate modifier form
     */
    validateModifierForm() {
        const form = document.getElementById('modifier-item-form');
        if (!form) return false;

        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                this.showFieldError(field, 'This field is required');
                isValid = false;
            } else {
                this.clearFieldError(field);
            }
        });

        return isValid;
    }

    /**
     * Save group
     */
    saveGroup() {
        this.showLoading();
        
        // Simulate API call
        setTimeout(() => {
            const formData = this.getGroupFormData();
            formData.modifiers = [...this.currentModifiers];
            
            if (this.currentGroup) {
                // Update existing group
                const index = this.modifierGroups.findIndex(g => g.id === this.currentGroup.id);
                if (index !== -1) {
                    this.modifierGroups[index] = { ...this.currentGroup, ...formData, updated_at: new Date().toISOString() };
                }
                this.showNotification('Modifier group updated successfully', 'success');
            } else {
                // Create new group
                const newGroup = {
                    id: Date.now(),
                    ...formData,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString()
                };
                this.modifierGroups.push(newGroup);
                this.showNotification('Modifier group created successfully', 'success');
            }
            
            this.hideLoading();
            this.closeGroupModal();
            this.loadModifierGroups();
            this.updateStatistics();
        }, 1000);
    }

    /**
     * Save modifier
     */
    saveModifier() {
        const formData = this.getModifierFormData();
        
        if (this.currentModifier) {
            // Update existing modifier
            const index = this.currentModifiers.findIndex(m => m.id === this.currentModifier.id);
            if (index !== -1) {
                this.currentModifiers[index] = { ...this.currentModifier, ...formData };
            }
        } else {
            // Create new modifier
            const newModifier = {
                id: Date.now() + Math.random(),
                ...formData
            };
            this.currentModifiers.push(newModifier);
        }
        
        this.closeModifierModal();
        this.renderModifiersList();
        this.updateDefaultSelectionOptions();
        this.showNotification('Modifier saved successfully', 'success');
    }

    /**
     * Get group form data
     */
    getGroupFormData() {
        const form = document.getElementById('modifier-group-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'active' || key === 'required') {
                data[key] = true;
            } else {
                data[key] = value;
            }
        }

        // Handle unchecked checkboxes
        if (!formData.has('active')) data.active = false;
        if (!formData.has('required')) data.required = false;

        return data;
    }

    /**
     * Get modifier form data
     */
    getModifierFormData() {
        const form = document.getElementById('modifier-item-form');
        if (!form) return {};

        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            if (key === 'active') {
                data[key] = true;
            } else if (key === 'price') {
                data[key] = parseFloat(value) || 0;
            } else if (key === 'display_order') {
                data[key] = parseInt(value) || 0;
            } else {
                data[key] = value;
            }
        }

        // Handle unchecked checkbox
        if (!formData.has('active')) data.active = false;

        return data;
    }

    /**
     * Load modifier groups
     */
    loadModifierGroups() {
        this.showGroupsLoading();
        
        // Simulate API call
        setTimeout(() => {
            this.filteredGroups = [...this.modifierGroups];
            this.sortGroups('name');
            this.renderGroups();
            this.hideGroupsLoading();
            
            if (this.modifierGroups.length === 0) {
                this.showEmptyState();
            }
        }, 1000);
    }

    /**
     * Render groups
     */
    renderGroups() {
        const grid = document.getElementById('modifier-groups-grid');
        if (!grid) return;

        // Clear existing content except loading skeletons
        const nonLoadingCards = grid.querySelectorAll('.modifier-group-card:not(.loading)');
        nonLoadingCards.forEach(card => card.remove());

        this.filteredGroups.forEach(group => {
            const groupCard = this.createGroupCard(group);
            grid.appendChild(groupCard);
        });

        if (this.filteredGroups.length === 0 && this.modifierGroups.length > 0) {
            this.showEmptyState();
        } else {
            this.hideEmptyState();
        }
    }

    /**
     * Create group card
     */
    createGroupCard(group) {
        const card = document.createElement('div');
        card.className = 'modifier-group-card';
        card.onclick = () => this.openGroupDetailsModal(group);
        
        const modifiersPreview = group.modifiers?.slice(0, 3).map(modifier => 
            `<div class="modifier-preview">
                <span class="modifier-preview-name">${modifier.name}</span>
                <span class="modifier-preview-price">$${modifier.price.toFixed(2)}</span>
            </div>`
        ).join('') || '';
        
        const moreModifiers = (group.modifiers?.length || 0) > 3 ? 
            `<div class="modifier-preview">+${(group.modifiers?.length || 0) - 3} more</div>` : '';
        
        card.innerHTML = `
            <div class="group-header">
                <div class="group-info">
                    <div class="group-name">${group.name}</div>
                    <div class="group-type ${group.selection_type}">${group.selection_type === 'single' ? 'Single Select' : 'Multiple Select'}</div>
                </div>
                <div class="group-menu">
                    <button class="group-menu-btn" onclick="event.stopPropagation(); menuModifiersPage.editGroup(${group.id})">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="group-description">${group.description || 'No description provided'}</div>
            <div class="group-modifiers">
                <div class="group-modifiers-title">Modifiers (${group.modifiers?.length || 0})</div>
                <div class="modifiers-preview">
                    ${modifiersPreview}
                    ${moreModifiers}
                </div>
            </div>
            <div class="group-footer">
                <div class="group-stats">
                    <div class="stat-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                        <span>${group.modifiers?.length || 0} options</span>
                    </div>
                    <div class="stat-item">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <span>Order: ${group.display_order || 0}</span>
                    </div>
                </div>
                <div class="group-status">
                    <div class="status-indicator ${group.active ? 'active' : 'inactive'}"></div>
                    <span>${group.active ? 'Active' : 'Inactive'}</span>
                </div>
            </div>
        `;
        
        return card;
    }

    /**
     * Render modifiers list in modal
     */
    renderModifiersList() {
        const list = document.getElementById('modifiers-list');
        const empty = document.getElementById('modifiers-empty');
        
        if (!list || !empty) return;

        if (this.currentModifiers.length === 0) {
            list.style.display = 'none';
            empty.style.display = 'block';
            return;
        }

        list.style.display = 'block';
        empty.style.display = 'none';
        list.innerHTML = '';

        this.currentModifiers.forEach(modifier => {
            const item = this.createModifierItem(modifier);
            list.appendChild(item);
        });
    }

    /**
     * Create modifier item for list
     */
    createModifierItem(modifier) {
        const item = document.createElement('div');
        item.className = 'modifier-item';
        
        item.innerHTML = `
            <div class="modifier-info">
                <div class="modifier-name">${modifier.name}</div>
                <div class="modifier-details">
                    <span class="modifier-price">$${modifier.price.toFixed(2)}</span>
                    <span>Order: ${modifier.display_order || 0}</span>
                    <span class="status-indicator ${modifier.active ? 'active' : 'inactive'}">${modifier.active ? 'Active' : 'Inactive'}</span>
                </div>
            </div>
            <div class="modifier-actions">
                <button class="btn btn-sm btn-secondary" onclick="menuModifiersPage.editModifier(${modifier.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="menuModifiersPage.deleteModifier(${modifier.id})">Delete</button>
            </div>
        `;
        
        return item;
    }

    /**
     * Edit group
     */
    editGroup(groupId) {
        const group = this.modifierGroups.find(g => g.id === groupId);
        if (group) {
            this.openGroupModal(group);
        }
    }

    /**
     * Delete group
     */
    deleteGroup(groupId) {
        const group = this.modifierGroups.find(g => g.id === groupId);
        if (!group) return;

        const modifiersCount = group.modifiers?.length || 0;
        let confirmMessage = 'Are you sure you want to delete this modifier group?';
        
        if (modifiersCount > 0) {
            confirmMessage = `This group has ${modifiersCount} modifiers. Are you sure you want to delete it?`;
        }

        if (confirm(confirmMessage)) {
            this.modifierGroups = this.modifierGroups.filter(g => g.id !== groupId);
            this.loadModifierGroups();
            this.updateStatistics();
            this.showNotification('Modifier group deleted successfully', 'success');
        }
    }

    /**
     * Edit modifier
     */
    editModifier(modifierId) {
        const modifier = this.currentModifiers.find(m => m.id === modifierId);
        if (modifier) {
            this.openModifierModal(modifier);
        }
    }

    /**
     * Delete modifier
     */
    deleteModifier(modifierId) {
        if (confirm('Are you sure you want to delete this modifier?')) {
            this.currentModifiers = this.currentModifiers.filter(m => m.id !== modifierId);
            this.renderModifiersList();
            this.updateDefaultSelectionOptions();
            this.showNotification('Modifier deleted successfully', 'success');
        }
    }

    /**
     * Update default selection options
     */
    updateDefaultSelectionOptions() {
        const select = document.getElementById('default-selection');
        if (!select) return;

        // Clear existing options except first
        while (select.children.length > 1) {
            select.removeChild(select.lastChild);
        }

        // Add modifier options
        this.currentModifiers.forEach(modifier => {
            const option = document.createElement('option');
            option.value = modifier.id;
            option.textContent = modifier.name;
            select.appendChild(option);
        });
    }

    /**
     * Import modifiers
     */
    importModifiers() {
        // Create file input for import
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.json,.csv';
        input.onchange = (e) => {
            const file = e.target.files[0];
            if (file) {
                this.handleImportFile(file);
            }
        };
        input.click();
    }

    /**
     * Handle import file
     */
    handleImportFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const data = JSON.parse(e.target.result);
                if (Array.isArray(data)) {
                    // Add unique IDs and timestamps
                    const importedGroups = data.map(group => ({
                        ...group,
                        id: Date.now() + Math.random(),
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString(),
                        modifiers: (group.modifiers || []).map(modifier => ({
                            ...modifier,
                            id: Date.now() + Math.random()
                        }))
                    }));
                    
                    this.modifierGroups.push(...importedGroups);
                    this.loadModifierGroups();
                    this.updateStatistics();
                    this.showNotification(`Imported ${importedGroups.length} modifier groups successfully`, 'success');
                }
            } catch (error) {
                this.showNotification('Invalid file format', 'error');
            }
        };
        reader.readAsText(file);
    }

    /**
     * Export modifiers
     */
    exportModifiers() {
        const data = {
            modifier_groups: this.modifierGroups,
            exported_at: new Date().toISOString(),
            total_groups: this.modifierGroups.length,
            total_modifiers: this.modifierGroups.reduce((sum, g) => sum + (g.modifiers?.length || 0), 0)
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `modifiers-export-${new Date().toISOString().split('T')[0]}.json`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Modifiers exported successfully', 'success');
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalGroups = this.modifierGroups.length;
        const activeGroups = this.modifierGroups.filter(g => g.active).length;
        const totalModifiers = this.modifierGroups.reduce((sum, g) => sum + (g.modifiers?.length || 0), 0);
        
        // Calculate average price
        let totalPrice = 0;
        let priceCount = 0;
        this.modifierGroups.forEach(group => {
            group.modifiers?.forEach(modifier => {
                totalPrice += modifier.price || 0;
                priceCount++;
            });
        });
        const avgPrice = priceCount > 0 ? totalPrice / priceCount : 0;

        // Update stat cards
        document.getElementById('total-groups').textContent = totalGroups;
        document.getElementById('active-groups').textContent = activeGroups;
        document.getElementById('total-modifiers').textContent = totalModifiers;
        document.getElementById('avg-price').textContent = `$${avgPrice.toFixed(2)}`;
    }

    /**
     * Populate group details
     */
    populateGroupDetails(group) {
        const content = document.getElementById('group-details-content');
        if (!content || !group) return;

        const modifiersList = group.modifiers?.map(modifier => 
            `<div class="details-modifier-item">
                <span class="details-modifier-name">${modifier.name}</span>
                <span class="details-modifier-price">$${modifier.price.toFixed(2)}</span>
            </div>`
        ).join('') || '<p>No modifiers added yet.</p>';

        content.innerHTML = `
            <div class="details-header">
                <div class="details-info">
                    <div class="details-name">${group.name}</div>
                    <div class="details-description">${group.description || 'No description provided'}</div>
                    <div class="details-type ${group.selection_type}">${group.selection_type === 'single' ? 'Single Select' : 'Multiple Select'}</div>
                </div>
            </div>
            <div class="details-stats">
                <div class="details-stat">
                    <div class="details-stat-value">${group.modifiers?.length || 0}</div>
                    <div class="details-stat-label">Modifiers</div>
                </div>
                <div class="details-stat">
                    <div class="details-stat-value">${group.display_order || 0}</div>
                    <div class="details-stat-label">Display Order</div>
                </div>
                <div class="details-stat">
                    <div class="details-stat-value">${group.active ? 'Active' : 'Inactive'}</div>
                    <div class="details-stat-label">Status</div>
                </div>
                <div class="details-stat">
                    <div class="details-stat-value">${group.required ? 'Yes' : 'No'}</div>
                    <div class="details-stat-label">Required</div>
                </div>
            </div>
            <div class="details-modifiers">
                <h4>Modifier Options</h4>
                <div class="details-modifiers-list">
                    ${modifiersList}
                </div>
            </div>
        `;
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.modifierGroups = [
            {
                id: 1,
                name: 'Size Options',
                description: 'Choose your preferred size for this item',
                selection_type: 'single',
                display_order: 1,
                active: true,
                required: true,
                min_selections: 1,
                max_selections: 1,
                default_selection: null,
                price_display: 'show',
                created_at: '2024-01-15T10:00:00Z',
                updated_at: '2024-01-15T10:00:00Z',
                modifiers: [
                    { id: 101, name: 'Small', price: 0.00, display_order: 1, active: true, description: 'Regular size portion' },
                    { id: 102, name: 'Medium', price: 2.50, display_order: 2, active: true, description: 'Larger portion' },
                    { id: 103, name: 'Large', price: 4.00, display_order: 3, active: true, description: 'Extra large portion' }
                ]
            },
            {
                id: 2,
                name: 'Extra Toppings',
                description: 'Add extra toppings to customize your dish',
                selection_type: 'multiple',
                display_order: 2,
                active: true,
                required: false,
                min_selections: 0,
                max_selections: 5,
                default_selection: null,
                price_display: 'show',
                created_at: '2024-01-15T10:05:00Z',
                updated_at: '2024-01-15T10:05:00Z',
                modifiers: [
                    { id: 201, name: 'Extra Cheese', price: 1.50, display_order: 1, active: true, description: 'Additional cheese layer' },
                    { id: 202, name: 'Bacon', price: 2.00, display_order: 2, active: true, description: 'Crispy bacon strips' },
                    { id: 203, name: 'Mushrooms', price: 1.00, display_order: 3, active: true, description: 'Fresh mushrooms' },
                    { id: 204, name: 'Pepperoni', price: 1.75, display_order: 4, active: true, description: 'Spicy pepperoni slices' },
                    { id: 205, name: 'Olives', price: 0.75, display_order: 5, active: true, description: 'Black or green olives' }
                ]
            },
            {
                id: 3,
                name: 'Spice Level',
                description: 'Select your preferred spice level',
                selection_type: 'single',
                display_order: 3,
                active: true,
                required: true,
                min_selections: 1,
                max_selections: 1,
                default_selection: 302,
                price_display: 'hide',
                created_at: '2024-01-15T10:10:00Z',
                updated_at: '2024-01-15T10:10:00Z',
                modifiers: [
                    { id: 301, name: 'Mild', price: 0.00, display_order: 1, active: true, description: 'Light spice level' },
                    { id: 302, name: 'Medium', price: 0.00, display_order: 2, active: true, description: 'Moderate spice level' },
                    { id: 303, name: 'Hot', price: 0.00, display_order: 3, active: true, description: 'Spicy level' },
                    { id: 304, name: 'Extra Hot', price: 0.00, display_order: 4, active: true, description: 'Very spicy level' }
                ]
            },
            {
                id: 4,
                name: 'Side Dishes',
                description: 'Choose side dishes to accompany your meal',
                selection_type: 'multiple',
                display_order: 4,
                active: true,
                required: false,
                min_selections: 0,
                max_selections: 3,
                default_selection: null,
                price_display: 'show',
                created_at: '2024-01-15T10:15:00Z',
                updated_at: '2024-01-15T10:15:00Z',
                modifiers: [
                    { id: 401, name: 'French Fries', price: 3.50, display_order: 1, active: true, description: 'Crispy golden fries' },
                    { id: 402, name: 'Onion Rings', price: 4.00, display_order: 2, active: true, description: 'Battered onion rings' },
                    { id: 403, name: 'Coleslaw', price: 2.50, display_order: 3, active: true, description: 'Fresh cabbage salad' },
                    { id: 404, name: 'Garlic Bread', price: 3.00, display_order: 4, active: true, description: 'Toasted garlic bread' }
                ]
            },
            {
                id: 5,
                name: 'Drink Options',
                description: 'Select your beverage preference',
                selection_type: 'single',
                display_order: 5,
                active: false,
                required: false,
                min_selections: 0,
                max_selections: 1,
                default_selection: null,
                price_display: 'show',
                created_at: '2024-01-15T10:20:00Z',
                updated_at: '2024-01-15T10:20:00Z',
                modifiers: [
                    { id: 501, name: 'Soft Drink', price: 2.50, display_order: 1, active: true, description: 'Carbonated beverage' },
                    { id: 502, name: 'Fresh Juice', price: 3.50, display_order: 2, active: true, description: 'Freshly squeezed juice' },
                    { id: 503, name: 'Coffee', price: 2.00, display_order: 3, active: true, description: 'Hot brewed coffee' },
                    { id: 504, name: 'Tea', price: 1.50, display_order: 4, active: true, description: 'Hot or iced tea' }
                ]
            }
        ];
    }

    /**
     * Utility methods
     */
    showLoading() {
        this.isLoading = true;
    }

    hideLoading() {
        this.isLoading = false;
    }

    showGroupsLoading() {
        const loadingCards = document.querySelectorAll('.modifier-group-card.loading');
        loadingCards.forEach(card => card.style.display = 'block');
    }

    hideGroupsLoading() {
        const loadingCards = document.querySelectorAll('.modifier-group-card.loading');
        loadingCards.forEach(card => card.style.display = 'none');
    }

    showEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const grid = document.getElementById('modifier-groups-grid');
        
        if (emptyState) emptyState.style.display = 'block';
        if (grid) grid.style.display = 'none';
    }

    hideEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        const grid = document.getElementById('modifier-groups-grid');
        
        if (emptyState) emptyState.style.display = 'none';
        if (grid) grid.style.display = 'grid';
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

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        field.classList.add('error');
        const errorElement = document.createElement('div');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        
        field.parentNode.appendChild(errorElement);
    }

    clearFieldError(field) {
        field.classList.remove('error');
        const errorElement = field.parentNode.querySelector('.field-error');
        if (errorElement) {
            errorElement.remove();
        }
    }

    populateGroupForm(group) {
        const form = document.getElementById('modifier-group-form');
        if (!form || !group) return;

        // Populate basic fields
        Object.keys(group).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = group[key];
                } else if (input.type === 'radio') {
                    if (input.value === group[key]) {
                        input.checked = true;
                    }
                } else {
                    input.value = group[key];
                }
            }
        });

        // Handle selection type change
        this.handleSelectionTypeChange(group.selection_type);
    }

    populateModifierForm(modifier) {
        const form = document.getElementById('modifier-item-form');
        if (!form || !modifier) return;

        // Populate fields
        Object.keys(modifier).forEach(key => {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                if (input.type === 'checkbox') {
                    input.checked = modifier[key];
                } else {
                    input.value = modifier[key];
                }
            }
        });
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.menuModifiersPage = new MenuModifiersPage();
});
