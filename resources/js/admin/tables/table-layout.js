/**
 * Table Layout Designer JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles visual table layout design with responsive canvas system
 */

class TableLayoutDesigner {
    constructor() {
        this.rooms = [];
        this.tableTypes = [];
        this.tables = [];
        this.selectedRoom = null;
        this.selectedTable = null;
        this.selectedTool = 'select';
        this.zoomLevel = 1.0;
        this.gridSize = 20;
        this.isDragging = false;
        this.dragOffset = { x: 0, y: 0 };
        this.canvasSize = { width: 800, height: 600 };
        this.nextTableId = 1;
        
        this.init();
    }

    /**
     * Initialize the layout designer
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.populateSelectors();
        this.setupCanvas();
        this.setupResponsiveCanvas();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Room and type selection
        this.bindSelectionEvents();
        
        // Canvas interaction events
        this.bindCanvasEvents();
        
        // Tool events
        this.bindToolEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Mobile events
        this.bindMobileEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Zoom events
        this.bindZoomEvents();
        
        // Responsive events
        this.bindResponsiveEvents();
    }

    /**
     * Bind selection events
     */
    bindSelectionEvents() {
        const roomSelector = document.getElementById('room-selector');
        const tableTypeSelector = document.getElementById('table-type-selector');
        const tableCapacitySelector = document.getElementById('table-capacity-selector');
        const tableNumberInput = document.getElementById('table-number-input');
        const addTableBtn = document.querySelector('.add-table-to-layout-btn');

        if (roomSelector) {
            roomSelector.addEventListener('change', (e) => {
                this.selectRoom(e.target.value);
            });
        }

        if (tableTypeSelector) {
            tableTypeSelector.addEventListener('change', (e) => {
                this.updateCapacityOptions(e.target.value);
                this.updateTableIdPreview();
            });
        }

        if (tableCapacitySelector) {
            tableCapacitySelector.addEventListener('change', () => {
                this.updateTableIdPreview();
            });
        }

        if (tableNumberInput) {
            tableNumberInput.addEventListener('input', () => {
                this.updateTableIdPreview();
                this.validateAddTableForm();
            });
        }

        if (addTableBtn) {
            addTableBtn.addEventListener('click', () => {
                this.openAddTableModal();
            });
        }
    }

    /**
     * Bind canvas events
     */
    bindCanvasEvents() {
        const canvasArea = document.getElementById('canvas-area');
        const roomCanvas = document.getElementById('room-canvas');

        if (canvasArea && roomCanvas) {
            // Click to add table (when in add mode)
            roomCanvas.addEventListener('click', (e) => {
                if (this.selectedTool === 'add' && this.selectedRoom) {
                    this.addTableAtPosition(e);
                }
            });

            // Table interaction events
            roomCanvas.addEventListener('mousedown', (e) => {
                if (e.target.classList.contains('canvas-table')) {
                    this.handleTableMouseDown(e);
                }
            });

            // Mouse move for dragging
            document.addEventListener('mousemove', (e) => {
                if (this.isDragging) {
                    this.handleTableDrag(e);
                }
            });

            // Mouse up to stop dragging
            document.addEventListener('mouseup', () => {
                if (this.isDragging) {
                    this.stopTableDrag();
                }
            });

            // Right click context menu
            roomCanvas.addEventListener('contextmenu', (e) => {
                if (e.target.classList.contains('canvas-table')) {
                    e.preventDefault();
                    this.showContextMenu(e);
                }
            });

            // Click outside to hide context menu
            document.addEventListener('click', () => {
                this.hideContextMenu();
            });
        }
    }

    /**
     * Bind tool events
     */
    bindToolEvents() {
        document.querySelectorAll('.tool-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tool = e.target.closest('.tool-btn').dataset.tool;
                this.selectTool(tool);
            });
        });

        // Layout options
        const showGrid = document.getElementById('show-grid');
        const showNumbers = document.getElementById('show-numbers');
        const showCapacity = document.getElementById('show-capacity');
        const gridSnap = document.getElementById('grid-snap');

        if (showGrid) {
            showGrid.addEventListener('change', () => this.toggleGrid());
        }

        if (showNumbers) {
            showNumbers.addEventListener('change', () => this.toggleTableNumbers());
        }

        if (showCapacity) {
            showCapacity.addEventListener('change', () => this.toggleTableCapacity());
        }

        if (gridSnap) {
            gridSnap.addEventListener('change', () => {
                this.gridSnapEnabled = gridSnap.checked;
            });
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Add table modal
        this.bindModalCloseEvents('add-table-modal', () => this.closeAddTableModal());

        // Context menu actions
        document.addEventListener('click', (e) => {
            if (e.target.closest('.context-menu-item')) {
                const action = e.target.closest('.context-menu-item').dataset.action;
                this.handleContextMenuAction(action);
            }
        });
    }

    /**
     * Bind mobile events
     */
    bindMobileEvents() {
        const mobileToggle = document.getElementById('mobile-panel-toggle');
        const mobilePanel = document.getElementById('mobile-layout-panel');
        const mobileRoomSelector = document.getElementById('mobile-room-selector');

        if (mobileToggle && mobilePanel) {
            mobileToggle.addEventListener('click', () => {
                mobilePanel.classList.toggle('expanded');
            });
        }

        if (mobileRoomSelector) {
            mobileRoomSelector.addEventListener('change', (e) => {
                this.selectRoom(e.target.value);
            });
        }
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const addTableForm = document.getElementById('add-table-form');
        if (addTableForm) {
            addTableForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.addTableToLayout();
            });
        }

        // Modal form inputs
        const modalTableType = document.getElementById('modal-table-type');
        const modalTableNumber = document.getElementById('modal-table-number');

        if (modalTableType) {
            modalTableType.addEventListener('change', (e) => {
                this.updateModalCapacityOptions(e.target.value);
                this.updateModalTableIdPreview();
            });
        }

        if (modalTableNumber) {
            modalTableNumber.addEventListener('input', () => {
                this.updateModalTableIdPreview();
            });
        }
    }

    /**
     * Bind zoom events
     */
    bindZoomEvents() {
        const zoomInBtn = document.getElementById('zoom-in-btn');
        const zoomOutBtn = document.getElementById('zoom-out-btn');
        const zoomFitBtn = document.getElementById('zoom-fit-btn');

        if (zoomInBtn) {
            zoomInBtn.addEventListener('click', () => this.zoomIn());
        }

        if (zoomOutBtn) {
            zoomOutBtn.addEventListener('click', () => this.zoomOut());
        }

        if (zoomFitBtn) {
            zoomFitBtn.addEventListener('click', () => this.zoomToFit());
        }

        // Mouse wheel zoom
        const canvasArea = document.getElementById('canvas-area');
        if (canvasArea) {
            canvasArea.addEventListener('wheel', (e) => {
                if (e.ctrlKey) {
                    e.preventDefault();
                    if (e.deltaY < 0) {
                        this.zoomIn();
                    } else {
                        this.zoomOut();
                    }
                }
            });
        }
    }

    /**
     * Bind responsive events
     */
    bindResponsiveEvents() {
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Initial resize
        this.handleResize();
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        // Rooms data (from rooms manager)
        this.rooms = [
            { id: 1, name: 'Main Dining Hall', code: 'MDH', capacity: 80 },
            { id: 2, name: 'Private Dining Room', code: 'PDR', capacity: 12 },
            { id: 3, name: 'Bar & Lounge', code: 'BAR', capacity: 25 },
            { id: 4, name: 'Garden Terrace', code: 'TER', capacity: 40 },
            { id: 5, name: 'VIP Section', code: 'VIP', capacity: 16 }
        ];

        // Table types data (from types manager)
        this.tableTypes = [
            { id: 1, name: '2-Seater Table', code: 'T2', shape: 'rectangle', minCapacity: 2, maxCapacity: 2 },
            { id: 2, name: '4-Seater Table', code: 'T4', shape: 'rectangle', minCapacity: 3, maxCapacity: 4 },
            { id: 3, name: '6-Seater Round Table', code: 'R6', shape: 'circle', minCapacity: 5, maxCapacity: 6 },
            { id: 4, name: '8-Seater Round Table', code: 'R8', shape: 'circle', minCapacity: 7, maxCapacity: 8 },
            { id: 5, name: 'Bar Stool', code: 'BS', shape: 'circle', minCapacity: 1, maxCapacity: 1 },
            { id: 6, name: 'Booth Table', code: 'BTH', shape: 'rectangle', minCapacity: 2, maxCapacity: 6 }
        ];
    }

    /**
     * Populate selectors
     */
    populateSelectors() {
        // Populate room selectors
        const roomSelectors = ['room-selector', 'mobile-room-selector'];
        roomSelectors.forEach(selectorId => {
            const selector = document.getElementById(selectorId);
            if (selector) {
                const options = this.rooms.map(room => 
                    `<option value="${room.id}">${room.name} (${room.code})</option>`
                ).join('');
                selector.innerHTML = selector.innerHTML + options;
            }
        });

        // Populate table type selectors
        const typeSelectors = ['table-type-selector', 'modal-table-type'];
        typeSelectors.forEach(selectorId => {
            const selector = document.getElementById(selectorId);
            if (selector) {
                const options = this.tableTypes.map(type => 
                    `<option value="${type.id}" data-shape="${type.shape}" data-min="${type.minCapacity}" data-max="${type.maxCapacity}">${type.name}</option>`
                ).join('');
                selector.innerHTML = selector.innerHTML + options;
            }
        });
    }

    /**
     * Setup canvas
     */
    setupCanvas() {
        const roomCanvas = document.getElementById('room-canvas');
        if (roomCanvas) {
            // Set initial canvas size
            roomCanvas.style.width = `${this.canvasSize.width}px`;
            roomCanvas.style.height = `${this.canvasSize.height}px`;
            
            // Initialize grid
            this.toggleGrid();
        }
    }

    /**
     * Setup responsive canvas system
     */
    setupResponsiveCanvas() {
        // Use CSS custom properties for responsive scaling
        const root = document.documentElement;
        
        // Base unit system (1 unit = 20px on desktop, scales down on mobile)
        const updateCanvasUnits = () => {
            const viewportWidth = window.innerWidth;
            let unitSize;
            
            if (viewportWidth <= 480) {
                unitSize = 12; // Smaller units on mobile
                this.canvasSize = { width: 400, height: 300 };
            } else if (viewportWidth <= 768) {
                unitSize = 16; // Medium units on tablet
                this.canvasSize = { width: 500, height: 400 };
            } else if (viewportWidth <= 1024) {
                unitSize = 18; // Slightly smaller on small desktop
                this.canvasSize = { width: 600, height: 450 };
            } else {
                unitSize = 20; // Full size on desktop
                this.canvasSize = { width: 800, height: 600 };
            }
            
            root.style.setProperty('--grid-size', `${unitSize}px`);
            root.style.setProperty('--canvas-min-width', `${this.canvasSize.width}px`);
            root.style.setProperty('--canvas-min-height', `${this.canvasSize.height}px`);
            
            this.gridSize = unitSize;
            this.updateCanvasSize();
        };

        updateCanvasUnits();
        window.addEventListener('resize', updateCanvasUnits);
    }

    /**
     * Handle responsive resize
     */
    handleResize() {
        // Recalculate table positions as percentages for responsiveness
        this.tables.forEach(table => {
            if (table.element) {
                this.updateTablePosition(table);
            }
        });
        
        this.updateTableCount();
    }

    /**
     * Select room
     */
    selectRoom(roomId) {
        if (!roomId) {
            this.selectedRoom = null;
            this.hideCanvasContent();
            return;
        }

        this.selectedRoom = this.rooms.find(r => r.id == roomId);
        if (this.selectedRoom) {
            this.showCanvasContent();
            this.updateRoomInfo();
            this.loadRoomLayout();
            
            // Sync mobile selector
            const mobileSelector = document.getElementById('mobile-room-selector');
            if (mobileSelector) {
                mobileSelector.value = roomId;
            }
        }
    }

    /**
     * Show canvas content
     */
    showCanvasContent() {
        document.getElementById('canvas-empty-state').style.display = 'none';
        document.getElementById('table-creation-section').style.display = 'block';
        document.getElementById('layout-tools-section').style.display = 'block';
        document.getElementById('current-room-info').style.display = 'flex';
    }

    /**
     * Hide canvas content
     */
    hideCanvasContent() {
        document.getElementById('canvas-empty-state').style.display = 'flex';
        document.getElementById('table-creation-section').style.display = 'none';
        document.getElementById('layout-tools-section').style.display = 'none';
        document.getElementById('current-room-info').style.display = 'none';
        document.getElementById('table-properties-section').style.display = 'none';
    }

    /**
     * Update room info
     */
    updateRoomInfo() {
        if (this.selectedRoom) {
            document.getElementById('current-room-name').textContent = this.selectedRoom.name;
            document.getElementById('current-room-capacity').textContent = this.selectedRoom.capacity;
        }
    }

    /**
     * Update capacity options based on table type
     */
    updateCapacityOptions(typeId) {
        const capacitySelector = document.getElementById('table-capacity-selector');
        if (!capacitySelector || !typeId) return;

        const tableType = this.tableTypes.find(t => t.id == typeId);
        if (tableType) {
            const options = [];
            for (let i = tableType.minCapacity; i <= tableType.maxCapacity; i++) {
                options.push(`<option value="${i}">${i} people</option>`);
            }
            capacitySelector.innerHTML = '<option value="">Select capacity...</option>' + options.join('');
        }
    }

    /**
     * Update modal capacity options
     */
    updateModalCapacityOptions(typeId) {
        const capacitySelector = document.getElementById('modal-table-capacity');
        if (!capacitySelector || !typeId) return;

        const tableType = this.tableTypes.find(t => t.id == typeId);
        if (tableType) {
            const options = [];
            for (let i = tableType.minCapacity; i <= tableType.maxCapacity; i++) {
                options.push(`<option value="${i}">${i} people</option>`);
            }
            capacitySelector.innerHTML = '<option value="">Select capacity...</option>' + options.join('');
        }
    }

    /**
     * Update table ID preview
     */
    updateTableIdPreview() {
        const tableNumber = document.getElementById('table-number-input').value;
        const previewElement = document.getElementById('table-id-preview');
        
        if (this.selectedRoom && tableNumber) {
            const tableId = this.generateTableId(this.selectedRoom.code, tableNumber);
            previewElement.textContent = tableId;
        } else {
            previewElement.textContent = '---';
        }
    }

    /**
     * Update modal table ID preview
     */
    updateModalTableIdPreview() {
        const tableNumber = document.getElementById('modal-table-number').value;
        const previewElement = document.getElementById('modal-table-id-preview');
        
        if (this.selectedRoom && tableNumber) {
            const tableId = this.generateTableId(this.selectedRoom.code, tableNumber);
            previewElement.textContent = tableId;
        } else {
            previewElement.textContent = '---';
        }
    }

    /**
     * Generate table ID
     */
    generateTableId(roomCode, tableNumber) {
        return `${roomCode}${tableNumber}`;
    }

    /**
     * Validate add table form
     */
    validateAddTableForm() {
        const tableNumber = document.getElementById('table-number-input').value;
        const addBtn = document.querySelector('.add-table-to-layout-btn');
        
        const isValid = this.selectedRoom && tableNumber && !this.isTableNumberTaken(tableNumber);
        
        if (addBtn) {
            addBtn.disabled = !isValid;
        }
    }

    /**
     * Check if table number is taken
     */
    isTableNumberTaken(tableNumber) {
        const tableId = this.generateTableId(this.selectedRoom.code, tableNumber);
        return this.tables.some(t => t.tableId === tableId && t.roomId === this.selectedRoom.id);
    }

    /**
     * Open add table modal
     */
    openAddTableModal() {
        const modal = document.getElementById('add-table-modal');
        if (modal) {
            // Pre-fill room-specific data
            const modalTableNumber = document.getElementById('modal-table-number');
            if (modalTableNumber) {
                modalTableNumber.value = this.getNextTableNumber();
                this.updateModalTableIdPreview();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close add table modal
     */
    closeAddTableModal() {
        const modal = document.getElementById('add-table-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetAddTableForm();
        }
    }

    /**
     * Reset add table form
     */
    resetAddTableForm() {
        const form = document.getElementById('add-table-form');
        if (form) {
            form.reset();
        }
    }

    /**
     * Add table to layout
     */
    addTableToLayout() {
        const formData = new FormData(document.getElementById('add-table-form'));
        
        const tableData = {
            typeId: parseInt(formData.get('table_type')),
            capacity: parseInt(formData.get('capacity')),
            tableName: formData.get('table_name') || '',
            tableNumber: formData.get('table_number')
        };

        if (!tableData.typeId || !tableData.capacity || !tableData.tableNumber) {
            this.showNotification('Please fill in all required fields', 'error');
            return;
        }

        if (this.isTableNumberTaken(tableData.tableNumber)) {
            this.showNotification('Table number already exists in this room', 'error');
            return;
        }

        const tableType = this.tableTypes.find(t => t.id === tableData.typeId);
        const tableId = this.generateTableId(this.selectedRoom.code, tableData.tableNumber);

        const newTable = {
            id: this.nextTableId++,
            roomId: this.selectedRoom.id,
            tableId: tableId,
            tableNumber: tableData.tableNumber,
            tableName: tableData.tableName,
            typeId: tableData.typeId,
            typeName: tableType.name,
            shape: tableType.shape,
            capacity: tableData.capacity,
            x: 50, // Default position (percentage)
            y: 50,
            width: this.getDefaultTableWidth(tableType.shape, tableData.capacity),
            height: this.getDefaultTableHeight(tableType.shape, tableData.capacity),
            createdAt: new Date()
        };

        this.tables.push(newTable);
        this.renderTable(newTable);
        this.updateTableCount();
        this.updateMobileTableList();
        this.closeAddTableModal();
        
        this.showNotification(`Table ${tableId} added to layout`, 'success');
    }

    /**
     * Get default table dimensions based on shape and capacity
     */
    getDefaultTableWidth(shape, capacity) {
        const baseSize = Math.max(60, capacity * 15); // Minimum 60px, 15px per person
        
        switch (shape) {
            case 'rectangle':
                return baseSize * 1.4; // Wider rectangles
            case 'circle':
            case 'square':
                return baseSize;
            case 'oval':
                return baseSize * 1.6; // Wider ovals
            default:
                return baseSize;
        }
    }

    /**
     * Get default table height
     */
    getDefaultTableHeight(shape, capacity) {
        const baseSize = Math.max(60, capacity * 15);
        
        switch (shape) {
            case 'rectangle':
                return baseSize * 0.8; // Shorter rectangles
            case 'circle':
            case 'square':
                return baseSize;
            case 'oval':
                return baseSize * 0.7; // Shorter ovals
            default:
                return baseSize;
        }
    }

    /**
     * Render table on canvas
     */
    renderTable(table) {
        const canvasTables = document.getElementById('canvas-tables');
        if (!canvasTables) return;

        const tableElement = document.createElement('div');
        tableElement.className = `canvas-table ${table.shape}`;
        tableElement.dataset.tableId = table.id;
        tableElement.style.left = `${table.x}%`;
        tableElement.style.top = `${table.y}%`;
        tableElement.style.width = `${table.width}px`;
        tableElement.style.height = `${table.height}px`;
        
        // Table content
        const showNumbers = document.getElementById('show-numbers')?.checked;
        const showCapacity = document.getElementById('show-capacity')?.checked;
        
        let content = '';
        if (showNumbers) {
            content += `<div class="table-number">${table.tableNumber}</div>`;
        }
        if (table.tableName) {
            content += `<div class="table-name">${table.tableName}</div>`;
        }
        if (showCapacity) {
            content += `<div class="table-capacity">${table.capacity}p</div>`;
        }
        
        tableElement.innerHTML = `<div class="table-content">${content}</div>`;
        
        // Add to canvas
        canvasTables.appendChild(tableElement);
        table.element = tableElement;
        
        // Add appear animation
        setTimeout(() => {
            tableElement.classList.add('new');
        }, 10);
    }

    /**
     * Update canvas size
     */
    updateCanvasSize() {
        const roomCanvas = document.getElementById('room-canvas');
        if (roomCanvas) {
            roomCanvas.style.width = `${this.canvasSize.width}px`;
            roomCanvas.style.height = `${this.canvasSize.height}px`;
        }
    }

    /**
     * Load room layout
     */
    loadRoomLayout() {
        // Clear existing tables
        const canvasTables = document.getElementById('canvas-tables');
        if (canvasTables) {
            canvasTables.innerHTML = '';
        }

        // Filter tables for current room
        const roomTables = this.tables.filter(t => t.roomId === this.selectedRoom.id);
        
        // Render tables
        roomTables.forEach(table => {
            this.renderTable(table);
        });
        
        this.updateTableCount();
        this.updateMobileTableList();
    }

    /**
     * Update table count
     */
    updateTableCount() {
        const roomTables = this.selectedRoom 
            ? this.tables.filter(t => t.roomId === this.selectedRoom.id)
            : [];
        
        const countElement = document.getElementById('table-count');
        if (countElement) {
            countElement.textContent = roomTables.length;
        }
    }

    /**
     * Update mobile table list
     */
    updateMobileTableList() {
        const mobileTableList = document.getElementById('mobile-table-list');
        if (!mobileTableList) return;

        const roomTables = this.selectedRoom 
            ? this.tables.filter(t => t.roomId === this.selectedRoom.id)
            : [];

        if (roomTables.length === 0) {
            mobileTableList.innerHTML = `
                <div class="mobile-empty-state">
                    <p>No tables in this room yet. Use the desktop interface to add tables.</p>
                </div>
            `;
            return;
        }

        mobileTableList.innerHTML = roomTables.map(table => `
            <div class="mobile-table-item" data-table-id="${table.id}">
                <div class="mobile-table-info">
                    <div class="mobile-table-number">${table.tableId}</div>
                    <div class="mobile-table-details">${table.typeName} • ${table.capacity} people</div>
                </div>
                <div class="mobile-table-actions">
                    <button class="mobile-table-btn" data-action="select" title="Select Table">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Get next available table number
     */
    getNextTableNumber() {
        if (!this.selectedRoom) return '1';
        
        const roomTables = this.tables.filter(t => t.roomId === this.selectedRoom.id);
        const existingNumbers = roomTables.map(t => parseInt(t.tableNumber)).filter(n => !isNaN(n));
        
        let nextNumber = 1;
        while (existingNumbers.includes(nextNumber)) {
            nextNumber++;
        }
        
        return nextNumber.toString();
    }

    /**
     * Toggle grid visibility
     */
    toggleGrid() {
        const grid = document.getElementById('canvas-grid');
        const showGrid = document.getElementById('show-grid');
        
        if (grid && showGrid) {
            if (showGrid.checked) {
                grid.classList.add('show');
            } else {
                grid.classList.remove('show');
            }
        }
    }

    /**
     * Toggle table numbers
     */
    toggleTableNumbers() {
        this.tables.forEach(table => {
            if (table.element) {
                this.updateTableContent(table);
            }
        });
    }

    /**
     * Toggle table capacity
     */
    toggleTableCapacity() {
        this.tables.forEach(table => {
            if (table.element) {
                this.updateTableContent(table);
            }
        });
    }

    /**
     * Update table content
     */
    updateTableContent(table) {
        const showNumbers = document.getElementById('show-numbers')?.checked;
        const showCapacity = document.getElementById('show-capacity')?.checked;
        
        let content = '';
        if (showNumbers) {
            content += `<div class="table-number">${table.tableNumber}</div>`;
        }
        if (table.tableName) {
            content += `<div class="table-name">${table.tableName}</div>`;
        }
        if (showCapacity) {
            content += `<div class="table-capacity">${table.capacity}p</div>`;
        }
        
        if (table.element) {
            table.element.innerHTML = `<div class="table-content">${content}</div>`;
        }
    }

    /**
     * Select tool
     */
    selectTool(tool) {
        this.selectedTool = tool;
        
        // Update tool button states
        document.querySelectorAll('.tool-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-tool="${tool}"]`).classList.add('active');
        
        // Update cursor
        const roomCanvas = document.getElementById('room-canvas');
        if (roomCanvas) {
            roomCanvas.className = `room-canvas tool-${tool}`;
        }
    }

    /**
     * Zoom controls
     */
    zoomIn() {
        this.zoomLevel = Math.min(this.zoomLevel * 1.2, 3.0);
        this.applyZoom();
    }

    zoomOut() {
        this.zoomLevel = Math.max(this.zoomLevel / 1.2, 0.3);
        this.applyZoom();
    }

    zoomToFit() {
        this.zoomLevel = 1.0;
        this.applyZoom();
    }

    /**
     * Apply zoom
     */
    applyZoom() {
        const roomCanvas = document.getElementById('room-canvas');
        const zoomLevelElement = document.getElementById('zoom-level');
        
        if (roomCanvas) {
            roomCanvas.style.transform = `scale(${this.zoomLevel})`;
        }
        
        if (zoomLevelElement) {
            zoomLevelElement.textContent = `${Math.round(this.zoomLevel * 100)}%`;
        }
    }

    /**
     * Update table position (responsive)
     */
    updateTablePosition(table) {
        if (!table.element) return;
        
        // Use percentage-based positioning for responsiveness
        table.element.style.left = `${table.x}%`;
        table.element.style.top = `${table.y}%`;
    }

    /**
     * Bind modal close events
     */
    bindModalCloseEvents(modalId, closeCallback) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.cancel-add-table-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Show notification
     */
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

    /**
     * Handle table mouse down (for dragging)
     */
    handleTableMouseDown(e) {
        if (this.selectedTool !== 'move' && this.selectedTool !== 'select') return;
        
        e.preventDefault();
        const table = this.tables.find(t => t.element === e.target.closest('.canvas-table'));
        
        if (table) {
            this.selectedTable = table;
            this.selectTable(table);
            
            if (this.selectedTool === 'move') {
                this.startTableDrag(e, table);
            }
        }
    }

    /**
     * Select table
     */
    selectTable(table) {
        // Remove previous selection
        document.querySelectorAll('.canvas-table').forEach(el => {
            el.classList.remove('selected');
        });
        
        // Select new table
        if (table && table.element) {
            table.element.classList.add('selected');
            this.showTableProperties(table);
        }
    }

    /**
     * Show table properties panel
     */
    showTableProperties(table) {
        const propertiesSection = document.getElementById('table-properties-section');
        if (propertiesSection) {
            propertiesSection.style.display = 'block';
            
            document.getElementById('selected-table-number').value = table.tableNumber;
            document.getElementById('selected-table-name').value = table.tableName;
            document.getElementById('selected-table-capacity').value = table.capacity;
        }
    }

    /**
     * Start table drag
     */
    startTableDrag(e, table) {
        this.isDragging = true;
        const rect = table.element.getBoundingClientRect();
        const canvasRect = document.getElementById('room-canvas').getBoundingClientRect();
        
        this.dragOffset = {
            x: e.clientX - rect.left,
            y: e.clientY - rect.top
        };
        
        table.element.classList.add('dragging');
        document.body.style.cursor = 'grabbing';
    }

    /**
     * Handle table drag
     */
    handleTableDrag(e) {
        if (!this.isDragging || !this.selectedTable) return;
        
        const canvasRect = document.getElementById('room-canvas').getBoundingClientRect();
        const canvasWidth = canvasRect.width;
        const canvasHeight = canvasRect.height;
        
        // Calculate position as percentage for responsiveness
        let x = ((e.clientX - canvasRect.left - this.dragOffset.x) / canvasWidth) * 100;
        let y = ((e.clientY - canvasRect.top - this.dragOffset.y) / canvasHeight) * 100;
        
        // Constrain to canvas bounds
        x = Math.max(0, Math.min(x, 100 - (this.selectedTable.width / canvasWidth * 100)));
        y = Math.max(0, Math.min(y, 100 - (this.selectedTable.height / canvasHeight * 100)));
        
        // Snap to grid if enabled
        const gridSnap = document.getElementById('grid-snap')?.checked;
        if (gridSnap) {
            const gridSizePercent = (this.gridSize / canvasWidth) * 100;
            x = Math.round(x / gridSizePercent) * gridSizePercent;
            y = Math.round(y / gridSizePercent) * gridSizePercent;
        }
        
        // Update table position
        this.selectedTable.x = x;
        this.selectedTable.y = y;
        this.updateTablePosition(this.selectedTable);
    }

    /**
     * Stop table drag
     */
    stopTableDrag() {
        if (this.selectedTable && this.selectedTable.element) {
            this.selectedTable.element.classList.remove('dragging');
        }
        
        this.isDragging = false;
        document.body.style.cursor = '';
    }

    /**
     * Show context menu
     */
    showContextMenu(e) {
        e.preventDefault();
        const contextMenu = document.getElementById('table-context-menu');
        if (contextMenu) {
            contextMenu.style.display = 'block';
            contextMenu.style.left = `${e.pageX}px`;
            contextMenu.style.top = `${e.pageY}px`;
            
            // Store the table element for context actions
            const table = this.tables.find(t => t.element === e.target.closest('.canvas-table'));
            contextMenu.dataset.tableId = table ? table.id : '';
        }
    }

    /**
     * Hide context menu
     */
    hideContextMenu() {
        const contextMenu = document.getElementById('table-context-menu');
        if (contextMenu) {
            contextMenu.style.display = 'none';
        }
    }

    /**
     * Handle context menu actions
     */
    handleContextMenuAction(action) {
        const contextMenu = document.getElementById('table-context-menu');
        const tableId = parseInt(contextMenu.dataset.tableId);
        const table = this.tables.find(t => t.id === tableId);
        
        if (!table) return;
        
        switch (action) {
            case 'edit':
                this.editTable(table);
                break;
            case 'duplicate':
                this.duplicateTable(table);
                break;
            case 'delete':
                this.deleteTable(table);
                break;
        }
        
        this.hideContextMenu();
    }

    /**
     * Edit table
     */
    editTable(table) {
        // For now, just select the table and show properties
        this.selectTable(table);
        this.showNotification('Use the properties panel to edit table details', 'info');
    }

    /**
     * Duplicate table
     */
    duplicateTable(table) {
        const newTable = {
            ...table,
            id: this.nextTableId++,
            tableNumber: this.getNextTableNumber(),
            x: Math.min(table.x + 10, 80), // Offset position
            y: Math.min(table.y + 10, 80),
            createdAt: new Date()
        };
        
        newTable.tableId = this.generateTableId(this.selectedRoom.code, newTable.tableNumber);
        
        this.tables.push(newTable);
        this.renderTable(newTable);
        this.updateTableCount();
        this.updateMobileTableList();
        
        this.showNotification(`Table duplicated as ${newTable.tableId}`, 'success');
    }

    /**
     * Delete table
     */
    deleteTable(table) {
        if (confirm(`Delete table ${table.tableId}?`)) {
            // Remove from canvas
            if (table.element) {
                table.element.remove();
            }
            
            // Remove from array
            this.tables = this.tables.filter(t => t.id !== table.id);
            
            this.updateTableCount();
            this.updateMobileTableList();
            this.showNotification(`Table ${table.tableId} deleted`, 'success');
            
            // Hide properties if this table was selected
            if (this.selectedTable === table) {
                this.selectedTable = null;
                document.getElementById('table-properties-section').style.display = 'none';
            }
        }
    }

    /**
     * Save layout
     */
    saveLayout() {
        if (!this.selectedRoom) {
            this.showNotification('Please select a room first', 'warning');
            return;
        }
        
        const layoutData = {
            roomId: this.selectedRoom.id,
            roomName: this.selectedRoom.name,
            tables: this.tables.filter(t => t.roomId === this.selectedRoom.id).map(t => ({
                tableId: t.tableId,
                tableNumber: t.tableNumber,
                tableName: t.tableName,
                typeId: t.typeId,
                capacity: t.capacity,
                shape: t.shape,
                x: t.x,
                y: t.y,
                width: t.width,
                height: t.height
            })),
            canvasSize: this.canvasSize,
            savedAt: new Date()
        };
        
        // Save to localStorage for demo
        localStorage.setItem(`table-layout-${this.selectedRoom.id}`, JSON.stringify(layoutData));
        
        this.showNotification(`Layout saved for ${this.selectedRoom.name}`, 'success');
    }

    /**
     * Export layout
     */
    exportLayout() {
        if (!this.selectedRoom) {
            this.showNotification('Please select a room first', 'warning');
            return;
        }
        
        const roomTables = this.tables.filter(t => t.roomId === this.selectedRoom.id);
        const csvContent = this.generateLayoutCSV(roomTables);
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `table-layout-${this.selectedRoom.code}-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Layout exported successfully', 'success');
    }

    /**
     * Generate layout CSV
     */
    generateLayoutCSV(tables) {
        const headers = ['Table ID', 'Table Number', 'Table Name', 'Type', 'Shape', 'Capacity', 'X Position', 'Y Position', 'Width', 'Height'];
        
        const rows = tables.map(table => [
            table.tableId,
            table.tableNumber,
            table.tableName || '',
            table.typeName,
            table.shape,
            table.capacity,
            table.x.toFixed(2),
            table.y.toFixed(2),
            table.width,
            table.height
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.tableLayoutDesigner = new TableLayoutDesigner();
    
    // Bind save layout button
    const saveLayoutBtn = document.querySelector('.save-layout-btn');
    if (saveLayoutBtn) {
        saveLayoutBtn.addEventListener('click', () => {
            window.tableLayoutDesigner.saveLayout();
        });
    }
    
    // Bind export layout button
    const exportLayoutBtn = document.querySelector('.export-layout-btn');
    if (exportLayoutBtn) {
        exportLayoutBtn.addEventListener('click', () => {
            window.tableLayoutDesigner.exportLayout();
        });
    }
});
