/**
 * Rooms Management JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles restaurant room creation, editing, and management
 */

class RoomsManager {
    constructor() {
        this.rooms = [];
        this.filteredRooms = [];
        this.searchTerm = '';
        this.filters = {
            type: '',
            status: ''
        };
        this.currentRoom = null;
        this.isEditing = false;
        
        this.init();
    }

    /**
     * Initialize the rooms manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderRooms();
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
    }

    /**
     * Bind search and filter events
     */
    bindSearchEvents() {
        const roomsSearch = document.getElementById('rooms-search');
        const typeFilter = document.getElementById('type-filter');
        const statusFilter = document.getElementById('status-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (roomsSearch) {
            roomsSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderRooms();
            });
        }

        if (typeFilter) {
            typeFilter.addEventListener('change', (e) => {
                this.filters.type = e.target.value;
                this.filterAndRenderRooms();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.filterAndRenderRooms();
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
        // Room modal
        this.bindModalCloseEvents('room-modal', () => this.closeRoomModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeRoomModal();
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
        const cancelBtn = modal.querySelector('.cancel-room-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add room button
        document.querySelectorAll('.add-room-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openRoomModal());
        });

        // Export rooms button
        const exportBtn = document.querySelector('.export-rooms-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportRooms());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Room card click (for viewing details)
            if (e.target.closest('.room-card') && !e.target.closest('.room-action-btn')) {
                const roomId = parseInt(e.target.closest('.room-card').dataset.roomId);
                this.viewRoomDetails(roomId);
            }
            
            // Room action buttons
            if (e.target.closest('.room-action-btn')) {
                e.stopPropagation();
                const action = e.target.closest('.room-action-btn').dataset.action;
                const roomId = parseInt(e.target.closest('.room-card').dataset.roomId);
                
                if (action === 'edit') {
                    this.editRoom(roomId);
                } else if (action === 'delete') {
                    this.deleteRoom(roomId);
                }
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const roomForm = document.getElementById('room-form');
        if (roomForm) {
            roomForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveRoom();
            });
        }

        // Auto-generate room code from room name
        const roomNameInput = document.getElementById('room-name');
        const roomCodeInput = document.getElementById('room-code');
        
        if (roomNameInput && roomCodeInput) {
            roomNameInput.addEventListener('input', (e) => {
                if (!this.isEditing && !roomCodeInput.value) {
                    const code = this.generateRoomCode(e.target.value);
                    roomCodeInput.value = code;
                }
            });
        }
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.rooms = [
            {
                id: 1,
                name: 'Main Dining Hall',
                code: 'MDH',
                type: 'main_dining',
                capacity: 80,
                status: 'active',
                description: 'The primary dining area with elegant atmosphere and comfortable seating.',
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 2,
                name: 'Private Dining Room',
                code: 'PDR',
                type: 'private_dining',
                capacity: 12,
                status: 'active',
                description: 'Intimate private dining space perfect for special occasions and business meetings.',
                createdAt: new Date('2024-01-15'),
                updatedAt: new Date('2024-01-15')
            },
            {
                id: 3,
                name: 'Bar & Lounge',
                code: 'BAR',
                type: 'bar_area',
                capacity: 25,
                status: 'active',
                description: 'Stylish bar area with high tables and comfortable lounge seating.',
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 4,
                name: 'Garden Terrace',
                code: 'TER',
                type: 'terrace',
                capacity: 40,
                status: 'active',
                description: 'Beautiful outdoor terrace with garden views and fresh air dining.',
                createdAt: new Date('2024-01-16'),
                updatedAt: new Date('2024-01-16')
            },
            {
                id: 5,
                name: 'VIP Section',
                code: 'VIP',
                type: 'vip_section',
                capacity: 16,
                status: 'active',
                description: 'Exclusive VIP area with premium service and enhanced privacy.',
                createdAt: new Date('2024-01-17'),
                updatedAt: new Date('2024-01-17')
            },
            {
                id: 6,
                name: 'Banquet Hall',
                code: 'BAN',
                type: 'banquet_hall',
                capacity: 120,
                status: 'maintenance',
                description: 'Large banquet hall for events, weddings, and corporate functions.',
                createdAt: new Date('2024-01-18'),
                updatedAt: new Date('2024-02-01')
            }
        ];
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalRooms = this.rooms.length;
        const activeRooms = this.rooms.filter(r => r.status === 'active').length;
        const totalCapacity = this.rooms.reduce((sum, r) => sum + r.capacity, 0);

        document.getElementById('total-rooms').textContent = totalRooms;
        document.getElementById('active-rooms').textContent = activeRooms;
        document.getElementById('total-capacity').textContent = totalCapacity;
    }

    /**
     * Filter and render rooms
     */
    filterAndRenderRooms() {
        this.filteredRooms = this.rooms.filter(room => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                room.name.toLowerCase().includes(this.searchTerm) ||
                room.code.toLowerCase().includes(this.searchTerm) ||
                room.description.toLowerCase().includes(this.searchTerm);

            // Type filter
            const typeMatch = !this.filters.type || room.type === this.filters.type;

            // Status filter
            const statusMatch = !this.filters.status || room.status === this.filters.status;

            return searchMatch && typeMatch && statusMatch;
        });

        this.renderRooms();
    }

    /**
     * Render rooms
     */
    renderRooms() {
        const roomsGrid = document.getElementById('rooms-grid');
        if (!roomsGrid) return;

        const roomsToShow = this.filteredRooms.length ? this.filteredRooms : this.rooms;

        if (roomsToShow.length === 0) {
            roomsGrid.innerHTML = `
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <h3>No rooms found</h3>
                    <p>No rooms match your current search and filter criteria.</p>
                </div>
            `;
            return;
        }

        roomsGrid.innerHTML = roomsToShow.map(room => `
            <div class="room-card" data-room-id="${room.id}" data-type="${room.type}">
                <div class="room-header">
                    <div class="room-info">
                        <div class="room-name">${room.name}</div>
                        <div class="room-code">${room.code}</div>
                    </div>
                    <div class="room-status ${room.status}">${this.formatStatus(room.status)}</div>
                </div>
                
                <div class="room-details">
                    <div class="room-detail">
                        <span class="detail-label">Type</span>
                        <span class="detail-value">${this.formatRoomType(room.type)}</span>
                    </div>
                    <div class="room-detail">
                        <span class="detail-label">Capacity</span>
                        <span class="detail-value">${room.capacity} people</span>
                    </div>
                    <div class="room-detail">
                        <span class="detail-label">Created</span>
                        <span class="detail-value">${this.formatDate(room.createdAt)}</span>
                    </div>
                </div>
                
                ${room.description ? `<div class="room-description">${room.description}</div>` : ''}
                
                <div class="room-actions">
                    <button class="room-action-btn edit" data-action="edit" title="Edit Room">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="room-action-btn delete" data-action="delete" title="Delete Room">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Open room modal
     */
    openRoomModal(room = null) {
        this.currentRoom = room;
        this.isEditing = !!room;
        
        const modal = document.getElementById('room-modal');
        const title = document.getElementById('room-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Room' : 'Add Room';
            
            if (this.isEditing) {
                this.populateRoomForm(room);
            } else {
                this.resetRoomForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close room modal
     */
    closeRoomModal() {
        const modal = document.getElementById('room-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetRoomForm();
            this.currentRoom = null;
            this.isEditing = false;
        }
    }

    /**
     * Populate room form
     */
    populateRoomForm(room) {
        document.getElementById('room-name').value = room.name;
        document.getElementById('room-code').value = room.code;
        document.getElementById('room-type').value = room.type;
        document.getElementById('room-capacity').value = room.capacity;
        document.getElementById('room-status').value = room.status;
        document.getElementById('room-description').value = room.description || '';
    }

    /**
     * Reset room form
     */
    resetRoomForm() {
        const form = document.getElementById('room-form');
        if (form) {
            form.reset();
            document.getElementById('room-status').value = 'active';
        }
    }

    /**
     * Save room
     */
    saveRoom() {
        const formData = new FormData(document.getElementById('room-form'));
        
        const roomData = {
            name: formData.get('room_name'),
            code: formData.get('room_code').toUpperCase(),
            type: formData.get('room_type'),
            capacity: parseInt(formData.get('capacity')),
            status: formData.get('status'),
            description: formData.get('description') || ''
        };

        // Validate required fields
        if (!roomData.name || !roomData.code || !roomData.type || !roomData.capacity) {
            this.showNotification('Please fill in all required fields', 'error');
            return;
        }

        // Check for duplicate room code
        const duplicateCode = this.rooms.find(r => 
            r.code === roomData.code && (!this.isEditing || r.id !== this.currentRoom.id)
        );
        
        if (duplicateCode) {
            this.showNotification('Room code already exists. Please use a different code.', 'error');
            return;
        }

        if (this.isEditing) {
            // Update existing room
            const index = this.rooms.findIndex(r => r.id === this.currentRoom.id);
            if (index !== -1) {
                this.rooms[index] = { 
                    ...this.rooms[index], 
                    ...roomData, 
                    updatedAt: new Date() 
                };
                this.showNotification('Room updated successfully', 'success');
            }
        } else {
            // Add new room
            const newRoom = {
                id: Math.max(...this.rooms.map(r => r.id)) + 1,
                ...roomData,
                createdAt: new Date(),
                updatedAt: new Date()
            };
            this.rooms.push(newRoom);
            this.showNotification('Room added successfully', 'success');
        }

        this.updateStatistics();
        this.filterAndRenderRooms();
        this.closeRoomModal();
    }

    /**
     * Edit room
     */
    editRoom(roomId) {
        const room = this.rooms.find(r => r.id === roomId);
        if (room) {
            this.openRoomModal(room);
        }
    }

    /**
     * Delete room
     */
    deleteRoom(roomId) {
        const room = this.rooms.find(r => r.id === roomId);
        if (room && confirm(`Are you sure you want to delete "${room.name}"?`)) {
            this.rooms = this.rooms.filter(r => r.id !== roomId);
            this.updateStatistics();
            this.filterAndRenderRooms();
            this.showNotification('Room deleted successfully', 'success');
        }
    }

    /**
     * View room details
     */
    viewRoomDetails(roomId) {
        const room = this.rooms.find(r => r.id === roomId);
        if (room) {
            // For now, just edit the room
            this.editRoom(roomId);
        }
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            type: '',
            status: ''
        };
        
        // Reset form inputs
        const roomsSearch = document.getElementById('rooms-search');
        const typeFilter = document.getElementById('type-filter');
        const statusFilter = document.getElementById('status-filter');
        
        if (roomsSearch) roomsSearch.value = '';
        if (typeFilter) typeFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        
        this.filterAndRenderRooms();
    }

    /**
     * Export rooms
     */
    exportRooms() {
        const csvContent = this.generateRoomsCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `rooms-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Rooms exported successfully', 'success');
    }

    /**
     * Generate rooms CSV
     */
    generateRoomsCSV() {
        const headers = ['ID', 'Name', 'Code', 'Type', 'Capacity', 'Status', 'Description', 'Created'];
        
        const rows = this.rooms.map(room => [
            room.id,
            room.name,
            room.code,
            this.formatRoomType(room.type),
            room.capacity,
            this.formatStatus(room.status),
            room.description || '',
            room.createdAt.toISOString().split('T')[0]
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }

    /**
     * Generate room code from name
     */
    generateRoomCode(name) {
        if (!name) return '';
        
        // Take first 3 characters of each word, max 5 characters
        const words = name.trim().split(/\s+/);
        let code = '';
        
        for (const word of words) {
            if (code.length >= 5) break;
            const chars = word.replace(/[^a-zA-Z]/g, '').toUpperCase();
            if (chars.length > 0) {
                code += chars.substring(0, Math.min(3, 5 - code.length));
            }
        }
        
        return code.substring(0, 5);
    }

    /**
     * Utility methods
     */
    formatRoomType(type) {
        const typeMap = {
            main_dining: 'Main Dining Room',
            private_dining: 'Private Dining Room',
            bar_area: 'Bar Area',
            outdoor_patio: 'Outdoor Patio',
            vip_section: 'VIP Section',
            banquet_hall: 'Banquet Hall',
            terrace: 'Terrace',
            lounge: 'Lounge'
        };
        return typeMap[type] || type;
    }

    formatStatus(status) {
        const statusMap = {
            active: 'Active',
            inactive: 'Inactive',
            maintenance: 'Under Maintenance'
        };
        return statusMap[status] || status;
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
    window.roomsManager = new RoomsManager();
});
