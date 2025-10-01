/**
 * Customer Reservations JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles reservation management, table booking, calendar views, and waitlist
 */

class ReservationsManager {
    constructor() {
        this.reservations = [];
        this.tables = [];
        this.waitlist = [];
        this.filteredReservations = [];
        this.currentDate = new Date();
        this.calendarView = 'month';
        this.searchTerm = '';
        this.filters = {
            status: '',
            date: '',
            table: ''
        };
        this.currentReservation = null;
        this.currentTable = null;
        this.isEditing = false;
        this.timeSlots = [];
        
        this.init();
    }

    /**
     * Initialize the reservations manager
     */
    init() {
        this.generateTimeSlots();
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderCalendar();
        this.renderReservationsList();
        this.renderTables();
        this.renderWaitlist();
        this.populateTableFilters();
    }

    /**
     * Generate time slots for reservations
     */
    generateTimeSlots() {
        this.timeSlots = [];
        for (let hour = 11; hour <= 22; hour++) {
            for (let minute = 0; minute < 60; minute += 30) {
                const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                const displayTime = this.formatTime24to12(time);
                this.timeSlots.push({ value: time, display: displayTime });
            }
        }
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // Calendar events
        this.bindCalendarEvents();
        
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
        const reservationsSearch = document.getElementById('reservations-search');
        const statusFilter = document.getElementById('status-filter');
        const dateFilter = document.getElementById('date-filter');
        const tableFilter = document.getElementById('table-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (reservationsSearch) {
            reservationsSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderReservations();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.filterAndRenderReservations();
            });
        }

        if (dateFilter) {
            dateFilter.addEventListener('change', (e) => {
                this.filters.date = e.target.value;
                this.filterAndRenderReservations();
            });
        }

        if (tableFilter) {
            tableFilter.addEventListener('change', (e) => {
                this.filters.table = e.target.value;
                this.filterAndRenderReservations();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind calendar events
     */
    bindCalendarEvents() {
        const calendarPrev = document.querySelector('.calendar-prev');
        const calendarNext = document.querySelector('.calendar-next');
        const todayBtn = document.querySelector('.today-btn');
        const calendarViewSelect = document.getElementById('calendar-view');

        if (calendarPrev) {
            calendarPrev.addEventListener('click', () => this.navigateCalendar(-1));
        }

        if (calendarNext) {
            calendarNext.addEventListener('click', () => this.navigateCalendar(1));
        }

        if (todayBtn) {
            todayBtn.addEventListener('click', () => this.goToToday());
        }

        if (calendarViewSelect) {
            calendarViewSelect.addEventListener('change', (e) => {
                this.calendarView = e.target.value;
                this.renderCalendar();
            });
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Reservation modal
        this.bindModalCloseEvents('reservation-modal', () => this.closeReservationModal());
        
        // Table modal
        this.bindModalCloseEvents('table-modal', () => this.closeTableModal());
        
        // Reservation details modal
        this.bindModalCloseEvents('reservation-details-modal', () => this.closeReservationDetails());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeReservationModal();
                this.closeTableModal();
                this.closeReservationDetails();
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
        const cancelBtn = modal.querySelector('.cancel-reservation-btn, .cancel-table-btn, .close-details-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add reservation button
        document.querySelectorAll('.add-reservation-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openReservationModal());
        });

        // Add table button
        const addTableBtn = document.querySelector('.add-table-btn');
        if (addTableBtn) {
            addTableBtn.addEventListener('click', () => this.openTableModal());
        }

        // Add to waitlist button
        const addToWaitlistBtn = document.querySelector('.add-to-waitlist-btn');
        if (addToWaitlistBtn) {
            addToWaitlistBtn.addEventListener('click', () => this.addToWaitlist());
        }

        // Table layout button
        const tableLayoutBtn = document.querySelector('.table-layout-btn');
        if (tableLayoutBtn) {
            tableLayoutBtn.addEventListener('click', () => this.openTableLayout());
        }

        // Export reservations button
        const exportBtn = document.querySelector('.export-reservations-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportReservations());
        }

        // Edit layout button
        const editLayoutBtn = document.querySelector('.edit-layout-btn');
        if (editLayoutBtn) {
            editLayoutBtn.addEventListener('click', () => this.editLayout());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Calendar day click
            if (e.target.closest('.calendar-day')) {
                const dayElement = e.target.closest('.calendar-day');
                const date = dayElement.dataset.date;
                if (date) {
                    this.selectCalendarDate(date);
                }
            }
            
            // Calendar event click
            if (e.target.closest('.calendar-event')) {
                e.stopPropagation();
                const reservationId = parseInt(e.target.closest('.calendar-event').dataset.reservationId);
                this.openReservationDetails(reservationId);
            }
            
            // Reservation action buttons
            if (e.target.closest('.action-btn.view')) {
                e.stopPropagation();
                const reservationId = parseInt(e.target.closest('tr').dataset.reservationId);
                this.openReservationDetails(reservationId);
            }
            
            if (e.target.closest('.action-btn.edit')) {
                e.stopPropagation();
                const reservationId = parseInt(e.target.closest('tr').dataset.reservationId);
                this.editReservation(reservationId);
            }
            
            if (e.target.closest('.action-btn.cancel')) {
                e.stopPropagation();
                const reservationId = parseInt(e.target.closest('tr').dataset.reservationId);
                this.cancelReservation(reservationId);
            }
            
            // Table item click
            if (e.target.closest('.table-item')) {
                const tableId = parseInt(e.target.closest('.table-item').dataset.tableId);
                this.selectTable(tableId);
            }
            
            // Waitlist actions
            if (e.target.closest('.seat-waitlist-btn')) {
                e.stopPropagation();
                const waitlistId = parseInt(e.target.closest('.waitlist-item').dataset.waitlistId);
                this.seatFromWaitlist(waitlistId);
            }
            
            if (e.target.closest('.remove-waitlist-btn')) {
                e.stopPropagation();
                const waitlistId = parseInt(e.target.closest('.waitlist-item').dataset.waitlistId);
                this.removeFromWaitlist(waitlistId);
            }
            
            // Reservation details actions
            if (e.target.closest('.confirm-reservation-btn')) {
                this.confirmReservation();
            }
            
            if (e.target.closest('.cancel-reservation-action-btn')) {
                this.cancelCurrentReservation();
            }
            
            if (e.target.closest('.edit-reservation-btn')) {
                this.editCurrentReservation();
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const reservationForm = document.getElementById('reservation-form');
        if (reservationForm) {
            reservationForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveReservation();
            });
        }

        const tableForm = document.getElementById('table-form');
        if (tableForm) {
            tableForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveTable();
            });
        }

        // Date and party size change events for table availability
        const reservationDate = document.getElementById('reservation-date');
        const reservationTime = document.getElementById('reservation-time');
        const partySize = document.getElementById('party-size');

        if (reservationDate) {
            reservationDate.addEventListener('change', () => this.updateAvailableTimeSlots());
        }

        if (reservationTime) {
            reservationTime.addEventListener('change', () => this.updateAvailableTables());
        }

        if (partySize) {
            partySize.addEventListener('change', () => this.updateAvailableTables());
        }
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.generateTables();
        this.generateReservations();
        this.generateWaitlist();
    }

    /**
     * Generate restaurant tables
     */
    generateTables() {
        this.tables = [
            { id: 1, number: 'T1', capacity: 2, type: 'regular', location: 'window', status: 'available', notes: 'Window view' },
            { id: 2, number: 'T2', capacity: 2, type: 'regular', location: 'main', status: 'occupied', notes: '' },
            { id: 3, number: 'T3', capacity: 4, type: 'booth', location: 'main', status: 'available', notes: 'Corner booth' },
            { id: 4, number: 'T4', capacity: 4, type: 'regular', location: 'main', status: 'reserved', notes: '' },
            { id: 5, number: 'T5', capacity: 6, type: 'regular', location: 'main', status: 'available', notes: 'Large table' },
            { id: 6, number: 'T6', capacity: 2, type: 'bar', location: 'bar_area', status: 'occupied', notes: 'Bar seating' },
            { id: 7, number: 'T7', capacity: 4, type: 'booth', location: 'window', status: 'available', notes: 'Window booth' },
            { id: 8, number: 'T8', capacity: 8, type: 'regular', location: 'main', status: 'available', notes: 'Family table' },
            { id: 9, number: 'T9', capacity: 2, type: 'outdoor', location: 'patio', status: 'maintenance', notes: 'Patio table' },
            { id: 10, number: 'T10', capacity: 6, type: 'regular', location: 'main', status: 'reserved', notes: '' },
            { id: 11, number: 'T11', capacity: 4, type: 'regular', location: 'main', status: 'available', notes: '' },
            { id: 12, number: 'T12', capacity: 2, type: 'regular', location: 'window', status: 'available', notes: 'Quiet area' },
            { id: 13, number: 'VIP1', capacity: 10, type: 'private', location: 'private_room', status: 'available', notes: 'Private dining room' },
            { id: 14, number: 'P1', capacity: 4, type: 'outdoor', location: 'patio', status: 'available', notes: 'Patio seating' },
            { id: 15, number: 'P2', capacity: 6, type: 'outdoor', location: 'patio', status: 'available', notes: 'Large patio table' }
        ];
    }

    /**
     * Generate reservations
     */
    generateReservations() {
        const firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria', 'William', 'Jennifer', 'Richard', 'Patricia', 'Charles', 'Linda'];
        const lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson'];
        const statuses = ['confirmed', 'pending', 'seated', 'completed', 'cancelled', 'no_show'];
        const occasions = ['', 'birthday', 'anniversary', 'business', 'celebration'];
        const specialRequests = [
            '', 'Wheelchair accessible', 'High chair needed', 'Quiet table please', 'Window seat preferred',
            'Birthday celebration', 'Anniversary dinner', 'Business meeting', 'Allergic to nuts', 'Vegetarian options needed'
        ];
        
        this.reservations = [];
        
        for (let i = 1; i <= 50; i++) {
            const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
            const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
            
            // Generate date within next 30 days
            const date = new Date();
            date.setDate(date.getDate() + Math.floor(Math.random() * 30) - 10);
            
            // Generate time
            const timeSlot = this.timeSlots[Math.floor(Math.random() * this.timeSlots.length)];
            
            // Generate party size
            const partySize = Math.floor(Math.random() * 8) + 1;
            
            // Select appropriate table
            const availableTables = this.tables.filter(t => t.capacity >= partySize);
            const table = availableTables[Math.floor(Math.random() * availableTables.length)];
            
            const reservation = {
                id: i,
                customerName: `${firstName} ${lastName}`,
                customerPhone: `+44 ${Math.floor(Math.random() * 9000) + 1000} ${Math.floor(Math.random() * 900000) + 100000}`,
                customerEmail: `${firstName.toLowerCase()}.${lastName.toLowerCase()}@example.com`,
                date: date,
                time: timeSlot.value,
                partySize: partySize,
                tableId: table ? table.id : null,
                tableNumber: table ? table.number : 'TBD',
                status: statuses[Math.floor(Math.random() * statuses.length)],
                specialRequests: specialRequests[Math.floor(Math.random() * specialRequests.length)],
                customerNotes: '',
                occasion: occasions[Math.floor(Math.random() * occasions.length)],
                seatingPreferences: [],
                dietaryRequirements: '',
                createdAt: new Date(Date.now() - Math.random() * 7 * 24 * 60 * 60 * 1000),
                confirmedAt: null,
                seatedAt: null,
                completedAt: null
            };
            
            // Set timestamps based on status
            if (['seated', 'completed'].includes(reservation.status)) {
                reservation.confirmedAt = new Date(reservation.createdAt.getTime() + Math.random() * 24 * 60 * 60 * 1000);
                reservation.seatedAt = new Date(reservation.date.getTime() + Math.random() * 30 * 60 * 1000);
            }
            
            if (reservation.status === 'completed') {
                reservation.completedAt = new Date(reservation.seatedAt.getTime() + (60 + Math.random() * 120) * 60 * 1000);
            }
            
            this.reservations.push(reservation);
        }
        
        // Sort by date and time
        this.reservations.sort((a, b) => {
            const aDateTime = new Date(`${a.date.toDateString()} ${a.time}`);
            const bDateTime = new Date(`${b.date.toDateString()} ${b.time}`);
            return aDateTime - bDateTime;
        });
    }

    /**
     * Generate waitlist
     */
    generateWaitlist() {
        const firstNames = ['Alex', 'Taylor', 'Jordan', 'Casey', 'Morgan'];
        const lastNames = ['Wilson', 'Thompson', 'Anderson', 'Clark', 'Lewis'];
        
        this.waitlist = [];
        
        for (let i = 1; i <= 8; i++) {
            const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
            const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
            const addedTime = new Date(Date.now() - Math.random() * 2 * 60 * 60 * 1000); // Within last 2 hours
            
            this.waitlist.push({
                id: i,
                position: i,
                customerName: `${firstName} ${lastName}`,
                customerPhone: `+44 ${Math.floor(Math.random() * 9000) + 1000} ${Math.floor(Math.random() * 900000) + 100000}`,
                partySize: Math.floor(Math.random() * 6) + 1,
                addedAt: addedTime,
                estimatedWait: Math.floor(Math.random() * 60) + 15, // 15-75 minutes
                notes: ''
            });
        }
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        const todayReservations = this.reservations.filter(r => {
            const resDate = new Date(r.date);
            resDate.setHours(0, 0, 0, 0);
            return resDate.getTime() === today.getTime();
        }).length;

        const upcomingReservations = this.reservations.filter(r => {
            const resDate = new Date(r.date);
            return resDate >= today && r.status === 'confirmed';
        }).length;

        const occupiedTables = this.tables.filter(t => t.status === 'occupied').length;
        const totalTables = this.tables.length;
        const occupancyRate = Math.round((occupiedTables / totalTables) * 100);

        const noShows = this.reservations.filter(r => r.status === 'no_show').length;

        document.getElementById('today-reservations').textContent = todayReservations;
        document.getElementById('upcoming-reservations').textContent = upcomingReservations;
        document.getElementById('table-occupancy').textContent = `${occupancyRate}%`;
        document.getElementById('no-shows').textContent = noShows;
    }

    /**
     * Navigate calendar
     */
    navigateCalendar(direction) {
        if (this.calendarView === 'month') {
            this.currentDate.setMonth(this.currentDate.getMonth() + direction);
        } else if (this.calendarView === 'week') {
            this.currentDate.setDate(this.currentDate.getDate() + (direction * 7));
        } else if (this.calendarView === 'day') {
            this.currentDate.setDate(this.currentDate.getDate() + direction);
        }
        this.renderCalendar();
    }

    /**
     * Go to today
     */
    goToToday() {
        this.currentDate = new Date();
        this.renderCalendar();
    }

    /**
     * Render calendar
     */
    renderCalendar() {
        const container = document.getElementById('calendar-container');
        const title = document.getElementById('calendar-title');
        
        if (!container || !title) return;

        // Update title
        if (this.calendarView === 'month') {
            title.textContent = this.currentDate.toLocaleDateString('en-US', { 
                month: 'long', 
                year: 'numeric' 
            });
        } else if (this.calendarView === 'week') {
            const startOfWeek = this.getStartOfWeek(this.currentDate);
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(endOfWeek.getDate() + 6);
            title.textContent = `${startOfWeek.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric' 
            })} - ${endOfWeek.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric' 
            })}`;
        } else {
            title.textContent = this.currentDate.toLocaleDateString('en-US', { 
                weekday: 'long',
                month: 'long', 
                day: 'numeric',
                year: 'numeric' 
            });
        }

        // Render calendar based on view
        if (this.calendarView === 'month') {
            this.renderMonthView(container);
        } else if (this.calendarView === 'week') {
            this.renderWeekView(container);
        } else {
            this.renderDayView(container);
        }
    }

    /**
     * Render month view
     */
    renderMonthView(container) {
        const firstDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth(), 1);
        const lastDay = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 0);
        const startDate = this.getStartOfWeek(firstDay);
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + 41); // 6 weeks

        let html = '<div class="calendar-grid">';
        
        // Header
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayNames.forEach(day => {
            html += `<div class="calendar-header-cell">${day}</div>`;
        });

        // Days
        const currentDate = new Date(startDate);
        while (currentDate <= endDate) {
            const isToday = this.isSameDay(currentDate, new Date());
            const isCurrentMonth = currentDate.getMonth() === this.currentDate.getMonth();
            const isWeekend = currentDate.getDay() === 0 || currentDate.getDay() === 6;
            
            const dayReservations = this.getReservationsForDate(currentDate);
            
            let dayClass = 'calendar-day';
            if (isToday) dayClass += ' today';
            if (!isCurrentMonth) dayClass += ' other-month';
            if (isWeekend) dayClass += ' weekend';
            
            html += `
                <div class="${dayClass}" data-date="${currentDate.toISOString().split('T')[0]}">
                    <div class="calendar-day-number">${currentDate.getDate()}</div>
                    <div class="calendar-events">
                        ${this.renderCalendarEvents(dayReservations)}
                    </div>
                </div>
            `;
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        html += '</div>';
        container.innerHTML = html;
    }

    /**
     * Render week view
     */
    renderWeekView(container) {
        // Simplified week view - similar to month but only 7 days
        const startOfWeek = this.getStartOfWeek(this.currentDate);
        
        let html = '<div class="calendar-grid">';
        
        // Header
        const dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayNames.forEach(day => {
            html += `<div class="calendar-header-cell">${day}</div>`;
        });

        // Days
        const currentDate = new Date(startOfWeek);
        for (let i = 0; i < 7; i++) {
            const isToday = this.isSameDay(currentDate, new Date());
            const isWeekend = currentDate.getDay() === 0 || currentDate.getDay() === 6;
            
            const dayReservations = this.getReservationsForDate(currentDate);
            
            let dayClass = 'calendar-day';
            if (isToday) dayClass += ' today';
            if (isWeekend) dayClass += ' weekend';
            
            html += `
                <div class="${dayClass}" data-date="${currentDate.toISOString().split('T')[0]}">
                    <div class="calendar-day-number">${currentDate.getDate()}</div>
                    <div class="calendar-events">
                        ${this.renderCalendarEvents(dayReservations)}
                    </div>
                </div>
            `;
            
            currentDate.setDate(currentDate.getDate() + 1);
        }
        
        html += '</div>';
        container.innerHTML = html;
    }

    /**
     * Render day view
     */
    renderDayView(container) {
        const dayReservations = this.getReservationsForDate(this.currentDate);
        
        let html = `
            <div class="day-view">
                <div class="day-header">
                    <h3>${this.currentDate.toLocaleDateString('en-US', { 
                        weekday: 'long',
                        month: 'long', 
                        day: 'numeric' 
                    })}</h3>
                    <p>${dayReservations.length} reservations</p>
                </div>
                <div class="day-reservations">
        `;
        
        if (dayReservations.length === 0) {
            html += '<p class="no-reservations">No reservations for this day</p>';
        } else {
            dayReservations.forEach(reservation => {
                html += `
                    <div class="day-reservation" data-reservation-id="${reservation.id}">
                        <div class="reservation-time">${this.formatTime24to12(reservation.time)}</div>
                        <div class="reservation-info">
                            <div class="reservation-customer">${reservation.customerName}</div>
                            <div class="reservation-details">
                                ${reservation.partySize} guests • Table ${reservation.tableNumber}
                            </div>
                        </div>
                        <div class="reservation-status ${reservation.status}">${this.formatStatus(reservation.status)}</div>
                    </div>
                `;
            });
        }
        
        html += '</div></div>';
        container.innerHTML = html;
    }

    /**
     * Render calendar events for a day
     */
    renderCalendarEvents(reservations) {
        if (reservations.length === 0) return '';
        
        let html = '';
        const maxVisible = 3;
        
        reservations.slice(0, maxVisible).forEach(reservation => {
            html += `
                <div class="calendar-event ${reservation.status}" data-reservation-id="${reservation.id}">
                    ${this.formatTime24to12(reservation.time)} ${reservation.customerName}
                </div>
            `;
        });
        
        if (reservations.length > maxVisible) {
            html += `<div class="calendar-event-more">+${reservations.length - maxVisible} more</div>`;
        }
        
        return html;
    }

    /**
     * Get reservations for a specific date
     */
    getReservationsForDate(date) {
        return this.reservations.filter(reservation => {
            return this.isSameDay(reservation.date, date);
        });
    }

    /**
     * Filter and render reservations list
     */
    filterAndRenderReservations() {
        this.filteredReservations = this.reservations.filter(reservation => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                reservation.customerName.toLowerCase().includes(this.searchTerm) ||
                reservation.customerPhone.includes(this.searchTerm) ||
                reservation.customerEmail.toLowerCase().includes(this.searchTerm);

            // Status filter
            const statusMatch = !this.filters.status || reservation.status === this.filters.status;

            // Date filter
            const dateMatch = !this.filters.date || 
                reservation.date.toISOString().split('T')[0] === this.filters.date;

            // Table filter
            const tableMatch = !this.filters.table || 
                reservation.tableId?.toString() === this.filters.table;

            return searchMatch && statusMatch && dateMatch && tableMatch;
        });

        this.renderReservationsList();
    }

    /**
     * Render reservations list
     */
    renderReservationsList() {
        const tableBody = document.getElementById('reservations-table-body');
        if (!tableBody) return;

        const reservationsToShow = this.filteredReservations.length ? this.filteredReservations : this.reservations;

        tableBody.innerHTML = reservationsToShow.map(reservation => `
            <tr data-reservation-id="${reservation.id}">
                <td class="reservation-datetime">
                    <div class="reservation-date">${this.formatDate(reservation.date)}</div>
                    <div class="reservation-time">${this.formatTime24to12(reservation.time)}</div>
                </td>
                <td class="reservation-customer">
                    <div>${reservation.customerName}</div>
                    <div class="customer-phone">${reservation.customerPhone}</div>
                </td>
                <td class="party-size">${reservation.partySize}</td>
                <td>
                    <span class="table-number">${reservation.tableNumber}</span>
                </td>
                <td>
                    <span class="reservation-status ${reservation.status}">
                        ${this.formatStatus(reservation.status)}
                    </span>
                </td>
                <td class="special-requests" title="${reservation.specialRequests}">
                    ${reservation.specialRequests}
                </td>
                <td class="reservation-actions">
                    <button class="action-btn view" title="View Details">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button class="action-btn edit" title="Edit Reservation">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="action-btn cancel" title="Cancel Reservation">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Render tables
     */
    renderTables() {
        this.renderRestaurantLayout();
        this.renderTablesList();
    }

    /**
     * Render restaurant layout
     */
    renderRestaurantLayout() {
        const layoutContainer = document.getElementById('restaurant-layout');
        if (!layoutContainer) return;

        let html = '<div class="layout-grid">';
        
        this.tables.forEach(table => {
            // Simple grid positioning (can be enhanced with drag & drop)
            const gridPosition = this.getTableGridPosition(table.id);
            
            html += `
                <div class="table-item ${table.status}" 
                     data-table-id="${table.id}"
                     style="grid-column: ${gridPosition.col}; grid-row: ${gridPosition.row};">
                    <div class="table-number-display">${table.number}</div>
                    <div class="table-capacity-display">${table.capacity} seats</div>
                    <div class="table-status-indicator ${table.status}"></div>
                </div>
            `;
        });
        
        html += '</div>';
        layoutContainer.innerHTML = html;
    }

    /**
     * Get table grid position (simplified positioning)
     */
    getTableGridPosition(tableId) {
        const positions = {
            1: { col: 1, row: 1 }, 2: { col: 3, row: 1 }, 3: { col: 5, row: 1 },
            4: { col: 7, row: 1 }, 5: { col: 9, row: 1 }, 6: { col: 11, row: 1 },
            7: { col: 1, row: 3 }, 8: { col: 3, row: 3 }, 9: { col: 5, row: 3 },
            10: { col: 7, row: 3 }, 11: { col: 9, row: 3 }, 12: { col: 11, row: 3 },
            13: { col: 2, row: 5 }, 14: { col: 5, row: 5 }, 15: { col: 8, row: 5 }
        };
        return positions[tableId] || { col: 1, row: 1 };
    }

    /**
     * Render tables list
     */
    renderTablesList() {
        const tablesGrid = document.getElementById('tables-grid');
        if (!tablesGrid) return;

        tablesGrid.innerHTML = this.tables.map(table => `
            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-card-number">${table.number}</div>
                    <span class="table-card-status ${table.status}">${this.formatStatus(table.status)}</span>
                </div>
                <div class="table-card-info">
                    <div class="table-info-item">
                        <span class="table-info-label">Capacity:</span>
                        <span class="table-info-value">${table.capacity} people</span>
                    </div>
                    <div class="table-info-item">
                        <span class="table-info-label">Type:</span>
                        <span class="table-info-value">${this.formatTableType(table.type)}</span>
                    </div>
                    <div class="table-info-item">
                        <span class="table-info-label">Location:</span>
                        <span class="table-info-value">${this.formatLocation(table.location)}</span>
                    </div>
                    ${table.notes ? `
                    <div class="table-info-item">
                        <span class="table-info-label">Notes:</span>
                        <span class="table-info-value">${table.notes}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        `).join('');
    }

    /**
     * Render waitlist
     */
    renderWaitlist() {
        const waitlistContainer = document.getElementById('waitlist-container');
        if (!waitlistContainer) return;

        if (this.waitlist.length === 0) {
            waitlistContainer.innerHTML = `
                <div class="empty-state">
                    <p>No customers currently on the waitlist</p>
                </div>
            `;
            return;
        }

        waitlistContainer.innerHTML = `
            <div class="waitlist-list">
                ${this.waitlist.map(item => `
                    <div class="waitlist-item" data-waitlist-id="${item.id}">
                        <div class="waitlist-info">
                            <div class="waitlist-position">${item.position}</div>
                            <div class="waitlist-customer">
                                <div class="waitlist-customer-name">${item.customerName}</div>
                                <div class="waitlist-customer-details">
                                    ${item.partySize} guests • ${item.customerPhone}
                                </div>
                            </div>
                        </div>
                        <div class="waitlist-time">
                            <div class="waitlist-wait-time">${item.estimatedWait} min</div>
                            <div class="waitlist-added-time">Added ${this.getTimeAgo(item.addedAt)}</div>
                        </div>
                        <div class="waitlist-actions">
                            <button class="btn btn-success seat-waitlist-btn">Seat Now</button>
                            <button class="btn btn-secondary remove-waitlist-btn">Remove</button>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    /**
     * Populate table filters
     */
    populateTableFilters() {
        const tableFilter = document.getElementById('table-filter');
        const tableSelection = document.getElementById('table-selection');
        
        if (tableFilter) {
            const options = this.tables.map(table => 
                `<option value="${table.id}">Table ${table.number}</option>`
            ).join('');
            tableFilter.innerHTML = '<option value="">All Tables</option>' + options;
        }
        
        if (tableSelection) {
            const options = this.tables.map(table => 
                `<option value="${table.id}">Table ${table.number} (${table.capacity} seats)</option>`
            ).join('');
            tableSelection.innerHTML = '<option value="">Auto Assign</option>' + options;
        }
    }

    /**
     * Update available time slots
     */
    updateAvailableTimeSlots() {
        const timeSelect = document.getElementById('reservation-time');
        if (!timeSelect) return;

        // For now, show all time slots (can be enhanced with availability logic)
        timeSelect.innerHTML = '<option value="">Select Time</option>' + 
            this.timeSlots.map(slot => 
                `<option value="${slot.value}">${slot.display}</option>`
            ).join('');
    }

    /**
     * Update available tables
     */
    updateAvailableTables() {
        const tableSelect = document.getElementById('table-selection');
        const partySize = document.getElementById('party-size')?.value;
        
        if (!tableSelect || !partySize) return;

        const suitableTables = this.tables.filter(table => 
            table.capacity >= parseInt(partySize) && table.status === 'available'
        );

        tableSelect.innerHTML = '<option value="">Auto Assign</option>' + 
            suitableTables.map(table => 
                `<option value="${table.id}">Table ${table.number} (${table.capacity} seats)</option>`
            ).join('');
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            status: '',
            date: '',
            table: ''
        };
        
        // Reset form inputs
        const reservationsSearch = document.getElementById('reservations-search');
        const statusFilter = document.getElementById('status-filter');
        const dateFilter = document.getElementById('date-filter');
        const tableFilter = document.getElementById('table-filter');
        
        if (reservationsSearch) reservationsSearch.value = '';
        if (statusFilter) statusFilter.value = '';
        if (dateFilter) dateFilter.value = '';
        if (tableFilter) tableFilter.value = '';
        
        this.filterAndRenderReservations();
    }

    /**
     * Open reservation modal
     */
    openReservationModal(reservation = null) {
        this.currentReservation = reservation;
        this.isEditing = !!reservation;
        
        const modal = document.getElementById('reservation-modal');
        const title = document.getElementById('reservation-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Reservation' : 'Add Reservation';
            
            if (this.isEditing) {
                this.populateReservationForm(reservation);
            } else {
                this.resetReservationForm();
            }
            
            this.updateAvailableTimeSlots();
            this.populateTimeSlots();
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close reservation modal
     */
    closeReservationModal() {
        const modal = document.getElementById('reservation-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetReservationForm();
            this.currentReservation = null;
            this.isEditing = false;
        }
    }

    /**
     * Populate time slots in form
     */
    populateTimeSlots() {
        const timeSelect = document.getElementById('reservation-time');
        if (!timeSelect) return;

        timeSelect.innerHTML = '<option value="">Select Time</option>' + 
            this.timeSlots.map(slot => 
                `<option value="${slot.value}">${slot.display}</option>`
            ).join('');
    }

    /**
     * Populate reservation form
     */
    populateReservationForm(reservation) {
        document.getElementById('reservation-date').value = reservation.date.toISOString().split('T')[0];
        document.getElementById('reservation-time').value = reservation.time;
        document.getElementById('party-size').value = reservation.partySize;
        document.getElementById('table-selection').value = reservation.tableId || '';
        document.getElementById('special-requests').value = reservation.specialRequests;
        document.getElementById('customer-name').value = reservation.customerName;
        document.getElementById('customer-phone').value = reservation.customerPhone;
        document.getElementById('customer-email').value = reservation.customerEmail;
        document.getElementById('reservation-status').value = reservation.status;
        document.getElementById('customer-notes').value = reservation.customerNotes;
        document.getElementById('dietary-requirements').value = reservation.dietaryRequirements;
        
        // Set occasion
        if (reservation.occasion) {
            const occasionRadio = document.querySelector(`input[name="occasion"][value="${reservation.occasion}"]`);
            if (occasionRadio) occasionRadio.checked = true;
        }
    }

    /**
     * Reset reservation form
     */
    resetReservationForm() {
        const form = document.getElementById('reservation-form');
        if (form) {
            form.reset();
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('reservation-date').value = today;
        }
    }

    /**
     * Save reservation
     */
    saveReservation() {
        const formData = new FormData(document.getElementById('reservation-form'));
        
        const reservationData = {
            customerName: formData.get('customer_name'),
            customerPhone: formData.get('customer_phone'),
            customerEmail: formData.get('customer_email'),
            date: new Date(formData.get('date')),
            time: formData.get('time'),
            partySize: parseInt(formData.get('party_size')),
            tableId: formData.get('table_id') ? parseInt(formData.get('table_id')) : null,
            status: formData.get('status'),
            specialRequests: formData.get('special_requests'),
            customerNotes: formData.get('customer_notes'),
            occasion: formData.get('occasion'),
            dietaryRequirements: formData.get('dietary_requirements'),
            seatingPreferences: Array.from(formData.getAll('seating_preferences[]'))
        };

        // Find table number
        if (reservationData.tableId) {
            const table = this.tables.find(t => t.id === reservationData.tableId);
            reservationData.tableNumber = table ? table.number : 'TBD';
        } else {
            reservationData.tableNumber = 'TBD';
        }

        if (this.isEditing) {
            // Update existing reservation
            const index = this.reservations.findIndex(r => r.id === this.currentReservation.id);
            if (index !== -1) {
                this.reservations[index] = { ...this.reservations[index], ...reservationData };
                this.showNotification('Reservation updated successfully', 'success');
            }
        } else {
            // Add new reservation
            const newReservation = {
                id: Math.max(...this.reservations.map(r => r.id)) + 1,
                ...reservationData,
                createdAt: new Date(),
                confirmedAt: null,
                seatedAt: null,
                completedAt: null
            };
            this.reservations.push(newReservation);
            this.showNotification('Reservation added successfully', 'success');
        }

        this.updateStatistics();
        this.renderCalendar();
        this.renderReservationsList();
        this.closeReservationModal();
    }

    /**
     * Open table modal
     */
    openTableModal(table = null) {
        this.currentTable = table;
        this.isEditing = !!table;
        
        const modal = document.getElementById('table-modal');
        const title = document.getElementById('table-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Table' : 'Add Table';
            
            if (this.isEditing) {
                this.populateTableForm(table);
            } else {
                this.resetTableForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close table modal
     */
    closeTableModal() {
        const modal = document.getElementById('table-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetTableForm();
            this.currentTable = null;
            this.isEditing = false;
        }
    }

    /**
     * Populate table form
     */
    populateTableForm(table) {
        document.getElementById('table-number').value = table.number;
        document.getElementById('table-capacity').value = table.capacity;
        document.getElementById('table-type').value = table.type;
        document.getElementById('table-location').value = table.location;
        document.getElementById('table-notes').value = table.notes;
    }

    /**
     * Reset table form
     */
    resetTableForm() {
        const form = document.getElementById('table-form');
        if (form) {
            form.reset();
        }
    }

    /**
     * Save table
     */
    saveTable() {
        const formData = new FormData(document.getElementById('table-form'));
        
        const tableData = {
            number: formData.get('number'),
            capacity: parseInt(formData.get('capacity')),
            type: formData.get('type'),
            location: formData.get('location'),
            notes: formData.get('notes'),
            status: 'available'
        };

        if (this.isEditing) {
            // Update existing table
            const index = this.tables.findIndex(t => t.id === this.currentTable.id);
            if (index !== -1) {
                this.tables[index] = { ...this.tables[index], ...tableData };
                this.showNotification('Table updated successfully', 'success');
            }
        } else {
            // Add new table
            const newTable = {
                id: Math.max(...this.tables.map(t => t.id)) + 1,
                ...tableData
            };
            this.tables.push(newTable);
            this.showNotification('Table added successfully', 'success');
        }

        this.renderTables();
        this.populateTableFilters();
        this.closeTableModal();
    }

    /**
     * Open reservation details modal
     */
    openReservationDetails(reservationId) {
        const reservation = this.reservations.find(r => r.id === reservationId);
        if (!reservation) return;
        
        this.currentReservation = reservation;
        
        const modal = document.getElementById('reservation-details-modal');
        const content = document.getElementById('reservation-details-content');
        
        if (modal && content) {
            content.innerHTML = this.generateReservationDetailsHtml(reservation);
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close reservation details modal
     */
    closeReservationDetails() {
        const modal = document.getElementById('reservation-details-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentReservation = null;
        }
    }

    /**
     * Generate reservation details HTML
     */
    generateReservationDetailsHtml(reservation) {
        const table = this.tables.find(t => t.id === reservation.tableId);
        
        return `
            <div class="reservation-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="reservation-info-section">
                    <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Reservation Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <strong>Date & Time:</strong> ${this.formatDate(reservation.date)} at ${this.formatTime24to12(reservation.time)}
                        </div>
                        <div>
                            <strong>Party Size:</strong> ${reservation.partySize} guests
                        </div>
                        <div>
                            <strong>Table:</strong> ${reservation.tableNumber} ${table ? `(${table.capacity} seats, ${this.formatLocation(table.location)})` : ''}
                        </div>
                        <div>
                            <strong>Status:</strong> <span class="reservation-status ${reservation.status}">${this.formatStatus(reservation.status)}</span>
                        </div>
                        ${reservation.occasion ? `
                        <div>
                            <strong>Occasion:</strong> ${this.formatOccasion(reservation.occasion)}
                        </div>
                        ` : ''}
                        ${reservation.specialRequests ? `
                        <div>
                            <strong>Special Requests:</strong> ${reservation.specialRequests}
                        </div>
                        ` : ''}
                        ${reservation.dietaryRequirements ? `
                        <div>
                            <strong>Dietary Requirements:</strong> ${reservation.dietaryRequirements}
                        </div>
                        ` : ''}
                    </div>
                </div>
                
                <div class="customer-info-section">
                    <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Customer Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <strong>Name:</strong> ${reservation.customerName}
                        </div>
                        <div>
                            <strong>Phone:</strong> ${reservation.customerPhone}
                        </div>
                        ${reservation.customerEmail ? `
                        <div>
                            <strong>Email:</strong> ${reservation.customerEmail}
                        </div>
                        ` : ''}
                        ${reservation.customerNotes ? `
                        <div>
                            <strong>Notes:</strong> ${reservation.customerNotes}
                        </div>
                        ` : ''}
                        <div>
                            <strong>Created:</strong> ${this.formatDateTime(reservation.createdAt)}
                        </div>
                        ${reservation.confirmedAt ? `
                        <div>
                            <strong>Confirmed:</strong> ${this.formatDateTime(reservation.confirmedAt)}
                        </div>
                        ` : ''}
                        ${reservation.seatedAt ? `
                        <div>
                            <strong>Seated:</strong> ${this.formatDateTime(reservation.seatedAt)}
                        </div>
                        ` : ''}
                        ${reservation.completedAt ? `
                        <div>
                            <strong>Completed:</strong> ${this.formatDateTime(reservation.completedAt)}
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Utility methods
     */
    isSameDay(date1, date2) {
        return date1.toDateString() === date2.toDateString();
    }

    getStartOfWeek(date) {
        const result = new Date(date);
        const day = result.getDay();
        const diff = result.getDate() - day;
        return new Date(result.setDate(diff));
    }

    formatDate(date) {
        return new Intl.DateTimeFormat('en-GB', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
    }

    formatDateTime(date) {
        return new Intl.DateTimeFormat('en-GB', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    }

    formatTime24to12(time24) {
        const [hours, minutes] = time24.split(':');
        const hour12 = ((parseInt(hours) + 11) % 12) + 1;
        const ampm = parseInt(hours) >= 12 ? 'PM' : 'AM';
        return `${hour12}:${minutes} ${ampm}`;
    }

    formatStatus(status) {
        const statusMap = {
            confirmed: 'Confirmed',
            pending: 'Pending',
            seated: 'Seated',
            completed: 'Completed',
            cancelled: 'Cancelled',
            no_show: 'No Show',
            available: 'Available',
            occupied: 'Occupied',
            reserved: 'Reserved',
            maintenance: 'Maintenance'
        };
        return statusMap[status] || status;
    }

    formatTableType(type) {
        const typeMap = {
            regular: 'Regular Table',
            booth: 'Booth',
            bar: 'Bar Seating',
            outdoor: 'Outdoor Table',
            private: 'Private Dining'
        };
        return typeMap[type] || type;
    }

    formatLocation(location) {
        const locationMap = {
            main: 'Main Dining',
            window: 'Window Area',
            patio: 'Patio',
            bar_area: 'Bar Area',
            private_room: 'Private Room'
        };
        return locationMap[location] || location;
    }

    formatOccasion(occasion) {
        const occasionMap = {
            birthday: 'Birthday',
            anniversary: 'Anniversary',
            business: 'Business Meeting',
            celebration: 'Celebration'
        };
        return occasionMap[occasion] || occasion;
    }

    getTimeAgo(date) {
        const now = new Date();
        const diffInMinutes = Math.floor((now - date) / (1000 * 60));
        
        if (diffInMinutes < 60) {
            return `${diffInMinutes}m ago`;
        } else {
            const hours = Math.floor(diffInMinutes / 60);
            return `${hours}h ago`;
        }
    }

    selectCalendarDate(dateString) {
        const date = new Date(dateString);
        this.currentDate = date;
        
        // Switch to day view
        this.calendarView = 'day';
        document.getElementById('calendar-view').value = 'day';
        this.renderCalendar();
    }

    selectTable(tableId) {
        const table = this.tables.find(t => t.id === tableId);
        if (table) {
            this.showNotification(`Selected Table ${table.number}`, 'info');
        }
    }

    editReservation(reservationId) {
        const reservation = this.reservations.find(r => r.id === reservationId);
        if (reservation) {
            this.openReservationModal(reservation);
        }
    }

    cancelReservation(reservationId) {
        if (confirm('Are you sure you want to cancel this reservation?')) {
            const reservation = this.reservations.find(r => r.id === reservationId);
            if (reservation) {
                reservation.status = 'cancelled';
                this.updateStatistics();
                this.renderCalendar();
                this.renderReservationsList();
                this.showNotification('Reservation cancelled', 'success');
            }
        }
    }

    confirmReservation() {
        if (this.currentReservation) {
            this.currentReservation.status = 'confirmed';
            this.currentReservation.confirmedAt = new Date();
            this.updateStatistics();
            this.renderCalendar();
            this.renderReservationsList();
            this.closeReservationDetails();
            this.showNotification('Reservation confirmed', 'success');
        }
    }

    cancelCurrentReservation() {
        if (this.currentReservation && confirm('Are you sure you want to cancel this reservation?')) {
            this.currentReservation.status = 'cancelled';
            this.updateStatistics();
            this.renderCalendar();
            this.renderReservationsList();
            this.closeReservationDetails();
            this.showNotification('Reservation cancelled', 'success');
        }
    }

    editCurrentReservation() {
        if (this.currentReservation) {
            this.closeReservationDetails();
            this.openReservationModal(this.currentReservation);
        }
    }

    addToWaitlist() {
        const name = prompt('Customer name:');
        const phone = prompt('Phone number:');
        const partySize = parseInt(prompt('Party size:'));
        
        if (name && phone && partySize) {
            const newWaitlistItem = {
                id: Math.max(...this.waitlist.map(w => w.id), 0) + 1,
                position: this.waitlist.length + 1,
                customerName: name,
                customerPhone: phone,
                partySize: partySize,
                addedAt: new Date(),
                estimatedWait: 30 + (this.waitlist.length * 15),
                notes: ''
            };
            
            this.waitlist.push(newWaitlistItem);
            this.renderWaitlist();
            this.showNotification('Customer added to waitlist', 'success');
        }
    }

    seatFromWaitlist(waitlistId) {
        const waitlistItem = this.waitlist.find(w => w.id === waitlistId);
        if (waitlistItem) {
            // Remove from waitlist
            this.waitlist = this.waitlist.filter(w => w.id !== waitlistId);
            
            // Update positions
            this.waitlist.forEach((item, index) => {
                item.position = index + 1;
            });
            
            this.renderWaitlist();
            this.showNotification(`${waitlistItem.customerName} seated successfully`, 'success');
        }
    }

    removeFromWaitlist(waitlistId) {
        if (confirm('Remove customer from waitlist?')) {
            this.waitlist = this.waitlist.filter(w => w.id !== waitlistId);
            
            // Update positions
            this.waitlist.forEach((item, index) => {
                item.position = index + 1;
            });
            
            this.renderWaitlist();
            this.showNotification('Customer removed from waitlist', 'success');
        }
    }

    openTableLayout() {
        this.showNotification('Table layout feature coming soon', 'info');
    }

    editLayout() {
        this.showNotification('Layout editing feature coming soon', 'info');
    }

    exportReservations() {
        const csvContent = this.generateReservationsCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `reservations-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Reservations exported successfully', 'success');
    }

    generateReservationsCSV() {
        const headers = [
            'ID', 'Customer Name', 'Phone', 'Email', 'Date', 'Time', 'Party Size',
            'Table', 'Status', 'Special Requests', 'Occasion', 'Created At'
        ];
        
        const rows = this.reservations.map(reservation => [
            reservation.id,
            reservation.customerName,
            reservation.customerPhone,
            reservation.customerEmail || '',
            reservation.date.toISOString().split('T')[0],
            reservation.time,
            reservation.partySize,
            reservation.tableNumber,
            reservation.status,
            reservation.specialRequests || '',
            reservation.occasion || '',
            reservation.createdAt.toISOString()
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
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
    window.reservationsManager = new ReservationsManager();
});
