/**
 * Staff Attendance - Admin View
 * Handles modal interactions, state machine operations, and real-time updates
 */

// Import modal portal system
import ModalPortal from './modal-portal.js';

// Modal state management
let addModal = null;
let editModal = null;
let startBreakModal = null;
let intervalsModal = null;
let reviewAttendanceModal = null;
let currentAttendanceId = null;

// Real-time update intervals
let dashboardUpdateInterval = null;
let activeSessionsUpdateInterval = null;

/**
 * Show the add attendance modal
 */
function showAddModal() {
    if (window.ModalPortal) {
        window.ModalPortal.showModal('addAttendanceModal');
    } else {
        // Fallback for legacy behavior
        addModal = document.getElementById('addAttendanceModal');
        if (addModal) {
            addModal.classList.remove('hidden');
            addModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
    }
    
            // Reset form and show all shifts when modal opens
            setTimeout(() => {
                const form = document.getElementById('addAttendanceForm');
                if (form) {
                    form.reset();
                }
                
                // Set default clock-in time to current time (user can change it)
                const clockInInput = document.getElementById('clockIn');
                if (clockInInput && !clockInInput.value) {
                    const now = new Date();
                    const year = now.getFullYear();
                    const month = String(now.getMonth() + 1).padStart(2, '0');
                    const day = String(now.getDate()).padStart(2, '0');
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    
                    clockInInput.value = `${year}-${month}-${day}T${hours}:${minutes}`;
                }
                
                // Show all shift templates initially
                const shiftSelect = document.getElementById('shift_template_id');
                if (shiftSelect) {
                    const options = shiftSelect.querySelectorAll('option');
                    options.forEach(option => {
                        option.style.display = 'block';
                    });
                }
            }, 100);
}

/**
 * Hide the add attendance modal
 */
function hideAddModal() {
    if (window.ModalPortal) {
        window.ModalPortal.hideModal('addAttendanceModal');
        // Reset form
        document.getElementById('addAttendanceForm')?.reset();
    } else {
        // Fallback for legacy behavior
        if (addModal) {
            addModal.classList.add('hidden');
            addModal.style.display = 'none';
            document.body.style.overflow = '';
            // Reset form
            document.getElementById('addAttendanceForm')?.reset();
        }
    }
}

/**
 * Show the edit attendance modal
 */
function showEditModal(attendanceId, staffName, clockIn, clockOut, status, notes) {
    editModal = document.getElementById('editAttendanceModal');
    if (!editModal) return;

    currentAttendanceId = attendanceId;

    // Populate form fields
    document.getElementById('editStaffName').textContent = staffName;
    document.getElementById('editClockIn').value = clockIn;
    document.getElementById('editClockOut').value = clockOut || '';
    document.getElementById('editStatus').value = status;
    document.getElementById('editNotes').value = notes || '';

    // Update form action
    const form = document.getElementById('editAttendanceForm');
    if (form) {
        form.action = `/admin/staff/attendance/${attendanceId}`;
    }

    // Show modal
    editModal.classList.remove('hidden');
    editModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

/**
 * Hide the edit attendance modal
 */
function hideEditModal() {
    if (editModal) {
        editModal.classList.add('hidden');
        editModal.classList.remove('flex');
        document.body.style.overflow = '';
        currentAttendanceId = null;
    }
}

/**
 * Show start break modal
 */
function showStartBreakModal(attendanceId, staffName) {
    startBreakModal = document.getElementById('startBreakModal');
    if (!startBreakModal) return;

    currentAttendanceId = attendanceId;
    
    // Update modal content
    document.getElementById('breakStaffName').textContent = staffName;
    
    // Show modal
    startBreakModal.classList.remove('hidden');
    startBreakModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

/**
 * Hide start break modal
 */
function hideStartBreakModal() {
    if (startBreakModal) {
        startBreakModal.classList.add('hidden');
        startBreakModal.classList.remove('flex');
        document.body.style.overflow = '';
        currentAttendanceId = null;
        // Reset form
        document.getElementById('startBreakForm')?.reset();
    }
}

/**
 * Show intervals modal
 */
function showIntervalsModal(attendanceId, staffName, date) {
    intervalsModal = document.getElementById('intervalsModal');
    if (!intervalsModal) return;

    currentAttendanceId = attendanceId;
    
    // Update modal content
    document.getElementById('intervalsStaffName').textContent = staffName;
    document.getElementById('intervalsDate').textContent = date;
    
    // Generate staff initials
    const nameParts = staffName.split(' ');
    const initials = nameParts.map(part => part.charAt(0)).join('').toUpperCase();
    document.getElementById('intervalsStaffInitials').textContent = initials;
    
    // Show modal
    intervalsModal.classList.remove('hidden');
    intervalsModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    // Load intervals data
    loadIntervals(attendanceId);
}

/**
 * Hide intervals modal
 */
function hideIntervalsModal() {
    if (intervalsModal) {
        intervalsModal.classList.add('hidden');
        intervalsModal.classList.remove('flex');
        document.body.style.overflow = '';
        currentAttendanceId = null;
    }
}

/**
 * Show review attendance modal
 */
function showReviewAttendanceModal(attendanceId, staffName, reviewReason, clockInTime) {
    reviewAttendanceModal = document.getElementById('reviewAttendanceModal');
    if (!reviewAttendanceModal) return;

    currentAttendanceId = attendanceId;
    
    // Update modal content
    document.getElementById('reviewStaffName').textContent = staffName;
    document.getElementById('reviewReasonText').textContent = reviewReason;
    document.getElementById('reviewClockInTime').textContent = clockInTime;
    
    // Show modal
    reviewAttendanceModal.classList.remove('hidden');
    reviewAttendanceModal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

/**
 * Hide review attendance modal
 */
function hideReviewAttendanceModal() {
    if (reviewAttendanceModal) {
        reviewAttendanceModal.classList.add('hidden');
        reviewAttendanceModal.classList.remove('flex');
        document.body.style.overflow = '';
        currentAttendanceId = null;
        // Reset form
        document.getElementById('reviewAttendanceForm')?.reset();
    }
}

/**
 * Start break for attendance record
 */
async function startBreak(attendanceId, breakType, reason) {
    try {
        const response = await fetch(`/admin/staff/attendance/${attendanceId}/start-break`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                break_category: breakType,
                reason: reason
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('Break started successfully', 'success');
            hideStartBreakModal();
            refreshPage();
        } else {
            showToast(data.message || 'Failed to start break', 'error');
        }
    } catch (error) {
        console.error('Error starting break:', error);
        showToast('Failed to start break', 'error');
    }
}

/**
 * Resume work for attendance record
 */
async function resumeWork(attendanceId) {
    try {
        const response = await fetch(`/admin/staff/attendance/${attendanceId}/resume-work`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('Work resumed successfully', 'success');
            refreshPage();
        } else {
            showToast(data.message || 'Failed to resume work', 'error');
        }
    } catch (error) {
        console.error('Error resuming work:', error);
        showToast('Failed to resume work', 'error');
    }
}

/**
 * Clock out attendance record
 */
async function clockOut(attendanceId) {
    if (!confirm('Are you sure you want to clock out this staff member?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/staff/attendance/${attendanceId}/clock-out`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('Staff member clocked out successfully', 'success');
            refreshPage();
        } else {
            showToast(data.message || 'Failed to clock out', 'error');
        }
    } catch (error) {
        console.error('Error clocking out:', error);
        showToast('Failed to clock out', 'error');
    }
}

/**
 * Auto close attendance record
 */
async function autoClose(attendanceId) {
    if (!confirm('Are you sure you want to auto-close this attendance session?')) {
        return;
    }

    try {
        const response = await fetch(`/admin/staff/attendance/${attendanceId}/auto-close`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('Attendance session auto-closed successfully', 'success');
            refreshPage();
        } else {
            showToast(data.message || 'Failed to auto-close', 'error');
        }
    } catch (error) {
        console.error('Error auto-closing:', error);
        showToast('Failed to auto-close', 'error');
    }
}

/**
 * Load intervals for attendance record
 */
async function loadIntervals(attendanceId) {
    try {
        const response = await fetch(`/admin/staff/attendance/${attendanceId}/intervals`);
        const data = await response.json();
        
        if (data.success) {
            updateIntervalsDisplay(data.intervals, data.summary);
        } else {
            showToast('Failed to load intervals', 'error');
        }
    } catch (error) {
        console.error('Error loading intervals:', error);
        showToast('Failed to load intervals', 'error');
    }
}

/**
 * Update intervals display in modal
 */
function updateIntervalsDisplay(intervals, summary) {
    // Update summary
    if (summary) {
        document.getElementById('totalWorkTime').textContent = summary.total_work_time || '0h 0m';
        document.getElementById('totalBreakTime').textContent = summary.total_break_time || '0h 0m';
        document.getElementById('breakCount').textContent = summary.break_count || '0';
    }
    
    // Update timeline (placeholder for now)
    const timeline = document.getElementById('intervalsTimeline');
    if (timeline) {
        if (intervals && intervals.length > 0) {
            timeline.innerHTML = '<p>Intervals timeline will be displayed here</p>';
        } else {
            timeline.innerHTML = '<p>No intervals recorded yet</p>';
        }
    }
}

/**
 * Complete review for attendance record
 */
async function completeReview(attendanceId, action, notes) {
    try {
        const response = await fetch(`/admin/staff/attendance/${attendanceId}/review`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                action: action,
                notes: notes
            })
        });

        const data = await response.json();
        
        if (data.success) {
            showToast('Review completed successfully', 'success');
            hideReviewAttendanceModal();
            refreshPage();
        } else {
            showToast(data.message || 'Failed to complete review', 'error');
        }
    } catch (error) {
        console.error('Error completing review:', error);
        showToast('Failed to complete review', 'error');
    }
}

/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    // Remove existing toasts
    const existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(toast => toast.remove());
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    
    const icon = type === 'success' ? '✓' : type === 'error' ? '✗' : 'ℹ';
    toast.innerHTML = `
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <text x="10" y="15" text-anchor="middle" font-size="14">${icon}</text>
        </svg>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.remove();
    }, 5000);
}

/**
 * Refresh the current page
 */
function refreshPage() {
    setTimeout(() => {
        window.location.reload();
    }, 1000);
}

/**
 * Update dashboard stats in real-time
 */
async function updateDashboardStats() {
    try {
        const response = await fetch('/admin/staff/attendance/dashboard-data');
        const data = await response.json();
        
        if (data.success) {
            // Update stat cards with new metric IDs
            updateStatCard('currently-working-count', data.data.stats.currently_working);
            updateStatCard('on-break-count', data.data.stats.on_break);
            updateStatCard('completed-today-count', data.data.stats.completed_today);
            updateStatCard('needs-review-count', data.data.stats.needs_review);
            
            // Update active sessions
            updateActiveSessionsList(data.data.currently_active);
            updateBreakStaffList(data.data.staff_on_break);
            updateReviewList(data.data.needs_review);
        }
    } catch (error) {
        console.error('Error updating dashboard stats:', error);
    }
}

/**
 * Update individual stat card
 */
function updateStatCard(cardId, value) {
    const card = document.getElementById(cardId);
    if (card) {
        const valueElement = card.querySelector('.stat-value');
        if (valueElement) {
            valueElement.textContent = value;
        }
    }
}

/**
 * Update active sessions list
 */
function updateActiveSessionsList(sessions) {
    const container = document.getElementById('activeSessionsList');
    if (!container) return;
    
    if (!sessions || sessions.length === 0) {
        container.innerHTML = '<p class="empty-state-text">No active sessions</p>';
        return;
    }
    
    container.innerHTML = sessions.map(session => `
        <div class="active-staff-item">
            <div class="active-staff-info">
                <div class="staff-indicator state-${session.current_state}">
                    <div class="staff-dot"></div>
                </div>
                <div class="staff-details">
                    <div class="staff-name">${session.staff.full_name}</div>
                    <div class="staff-role">${session.staff.staff_type?.display_name || 'N/A'}</div>
                    <div class="staff-state">
                        <span class="state-${session.current_state === 'clocked_in' ? 'working' : 'break'}">
                            ${session.current_state === 'clocked_in' ? 'Working' : 'On Break'}
                        </span>
                    </div>
                </div>
            </div>
            <div class="active-staff-time">
                <div class="clock-in-time">${session.clock_in_formatted}</div>
                <div class="duration">${session.duration}</div>
                ${session.current_state === 'on_break' ? `<div class="break-duration">Break: ${session.break_duration}</div>` : ''}
            </div>
        </div>
    `).join('');
}

/**
 * Update break staff list
 */
function updateBreakStaffList(staff) {
    const container = document.getElementById('breakStaffList');
    if (!container) return;
    
    if (!staff || staff.length === 0) {
        container.innerHTML = '<p class="empty-state-text">No staff on break</p>';
        return;
    }
    
    container.innerHTML = staff.map(record => `
        <div class="break-staff-item">
            <div class="break-staff-info">
                <div class="break-indicator">
                    <div class="break-dot"></div>
                </div>
                <div class="staff-details">
                    <div class="staff-name">${record.staff.full_name}</div>
                    <div class="staff-role">${record.staff.staff_type?.display_name || 'N/A'}</div>
                </div>
            </div>
            <div class="break-time-info">
                <div class="break-start">${record.break_start_formatted}</div>
                <div class="break-duration">${record.break_duration}</div>
            </div>
        </div>
    `).join('');
}

/**
 * Update review list
 */
function updateReviewList(reviews) {
    const container = document.getElementById('reviewList');
    if (!container) return;
    
    if (!reviews || reviews.length === 0) {
        container.innerHTML = '<p class="empty-state-text">All attendance records are approved</p>';
        return;
    }
    
    container.innerHTML = reviews.map(record => `
        <div class="review-item">
            <div class="review-info">
                <div class="review-icon">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="review-details">
                    <div class="staff-name">${record.staff.full_name}</div>
                    <div class="review-reason">${record.review_reason}</div>
                    <div class="review-time">${record.clock_in_formatted}</div>
                </div>
            </div>
        </div>
    `).join('');
}

/**
 * Delete attendance record
 */
function deleteAttendance(attendanceId, staffName) {
    if (!confirm(`Are you sure you want to delete the attendance record for ${staffName}?`)) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/staff/attendance/${attendanceId}`;

    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';

    form.appendChild(csrfInput);
    form.appendChild(methodInput);
    document.body.appendChild(form);
    form.submit();
}

/**
 * Close modal on backdrop click
 */
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        hideAddModal();
        hideEditModal();
        hideStartBreakModal();
        hideIntervalsModal();
        hideReviewAttendanceModal();
    }
});

/**
 * Close modal on Escape key
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideAddModal();
        hideEditModal();
        hideStartBreakModal();
        hideIntervalsModal();
        hideReviewAttendanceModal();
    }
});

/**
 * Form submissions
 */
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filters on change
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        const filterInputs = filterForm.querySelectorAll('.filter-input, .filter-select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }

    // Initialize datetime format for clock in/out inputs
    const clockInInput = document.getElementById('clockIn');
    const clockOutInput = document.getElementById('clockOut');

    if (clockInInput) {
        clockInInput.addEventListener('change', function() {
            // Auto-fill current time if empty
            if (!this.value) {
                const now = new Date();
                this.value = now.toISOString().slice(0, 16);
            }
        });
    }

    // Validate clock out is after clock in
    if (clockOutInput && clockInInput) {
        clockOutInput.addEventListener('change', function() {
            if (clockInInput.value && this.value) {
                const clockIn = new Date(clockInInput.value);
                const clockOut = new Date(this.value);

                if (clockOut <= clockIn) {
                    alert('Clock out time must be after clock in time');
                    this.value = '';
                }
            }
        });
    }

    // Start break form submission
    const startBreakForm = document.getElementById('startBreakForm');
    if (startBreakForm) {
        startBreakForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            startBreak(currentAttendanceId, formData.get('break_category'), formData.get('reason'));
        });
    }

    // Review attendance form submission
    const reviewAttendanceForm = document.getElementById('reviewAttendanceForm');
    if (reviewAttendanceForm) {
        reviewAttendanceForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            completeReview(currentAttendanceId, formData.get('action'), formData.get('notes'));
        });
    }

    // Start real-time updates
    if (window.location.pathname.includes('/admin/staff/attendance')) {
        // Update dashboard stats every 30 seconds
        dashboardUpdateInterval = setInterval(updateDashboardStats, 30000);
        
        // Initial update
        updateDashboardStats();
    }

    // Enhanced modal functionality
    initializeEnhancedModal();
});

// Cleanup intervals when leaving page
window.addEventListener('beforeunload', function() {
    if (dashboardUpdateInterval) {
        clearInterval(dashboardUpdateInterval);
    }
    if (activeSessionsUpdateInterval) {
        clearInterval(activeSessionsUpdateInterval);
    }
});

/**
 * Initialize enhanced modal functionality
 */
function initializeEnhancedModal() {
    // Character counter for notes textarea
    const notesTextarea = document.getElementById('notes');
    const notesCounter = document.getElementById('notesCounter');
    
    if (notesTextarea && notesCounter) {
        notesTextarea.addEventListener('input', function() {
            const length = this.value.length;
            notesCounter.textContent = length;
            
            // Change color based on length
            if (length > 450) {
                notesCounter.style.color = 'var(--color-danger-500)';
            } else if (length > 400) {
                notesCounter.style.color = 'var(--color-warning-500)';
            } else {
                notesCounter.style.color = 'var(--color-text-tertiary)';
            }
        });
    }

    // Enhanced select styling
    const enhancedSelects = document.querySelectorAll('.enhanced-select');
    enhancedSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Add visual feedback for status selection
            if (this.id === 'status') {
                const selectedOption = this.options[this.selectedIndex];
                const statusColor = selectedOption.getAttribute('data-status-color');
                if (statusColor) {
                    this.style.borderColor = `var(--color-${statusColor}-500)`;
                }
            }
        });
    });

    // Auto-focus first input when modal opens
    const addModal = document.getElementById('addAttendanceModal');
    if (addModal) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (!addModal.classList.contains('hidden')) {
                        const firstInput = addModal.querySelector('input, select, textarea');
                        if (firstInput) {
                            setTimeout(() => firstInput.focus(), 100);
                        }
                    }
                }
            });
        });
        observer.observe(addModal, { attributes: true });
    }

    // Shift assignment enhancement
    const staffSelect = document.getElementById('staff_id');
    const shiftSelect = document.getElementById('shift_template_id');
    
    if (staffSelect && shiftSelect) {
        // Initially show all shift templates
        const showAllShifts = () => {
            const options = shiftSelect.querySelectorAll('option');
            options.forEach(option => {
                option.style.display = 'block';
            });
        };
        
        // Initialize by showing all shifts
        showAllShifts();

        // When staff is selected, show all available shift templates
        staffSelect.addEventListener('change', function() {
            const selectedStaffId = this.value;
            const options = shiftSelect.querySelectorAll('option');
            let visibleCount = 0;
            
            if (!selectedStaffId) {
                // If no staff selected, show all shifts
                showAllShifts();
                return;
            }
            
            // Always show all shift templates - no filtering needed
            // The user can select any shift template for any staff member
            showAllShifts();
            
            console.log(`Staff selected: ${this.options[this.selectedIndex].textContent}`);
            console.log('All shift templates are now available for selection');
        });
        
        // When shift template is selected, auto-fill clock-in time
        shiftSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedStaff = staffSelect.options[staffSelect.selectedIndex];
            
            if (selectedOption.value && selectedStaff.value) {
                console.log(`Creating attendance: ${selectedStaff.textContent} -> ${selectedOption.textContent}`);
                
                // Auto-fill clock-in time with shift start time
                const shiftStartTime = selectedOption.getAttribute('data-start-time');
                if (shiftStartTime) {
                    // Get today's date
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const day = String(today.getDate()).padStart(2, '0');
                    
                    // Format the time for datetime-local input (YYYY-MM-DDTHH:MM)
                    const clockInInput = document.getElementById('clockIn');
                    if (clockInInput) {
                        const formattedDateTime = `${year}-${month}-${day}T${shiftStartTime}`;
                        clockInInput.value = formattedDateTime;
                        
                        // Add visual feedback
                        clockInInput.style.backgroundColor = '#f0f9ff';
                        clockInInput.style.borderColor = '#0ea5e9';
                        
                        // Remove highlight after 2 seconds
                        setTimeout(() => {
                            clockInInput.style.backgroundColor = '';
                            clockInInput.style.borderColor = '';
                        }, 2000);
                        
                        console.log(`Auto-filled clock-in time: ${formattedDateTime}`);
                    }
                }
            } else if (!selectedOption.value) {
                // If "No Shift Template" is selected, clear the clock-in time
                const clockInInput = document.getElementById('clockIn');
                if (clockInInput) {
                    clockInInput.value = '';
                }
            }
        });

        // Show all shifts when modal is first opened
        showAllShifts();
    }

    // Form validation enhancement
    const addForm = document.getElementById('addAttendanceForm');
    if (addForm) {
        addForm.addEventListener('submit', function(e) {
            // Debug: Log form data before submission
            const formData = new FormData(this);
            console.log('=== FORM SUBMISSION DEBUG ===');
            console.log('Clock-in input element value:', document.getElementById('clockIn').value);
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
                if (key === 'clock_in') {
                    console.log(`Clock-in field specifically: "${value}"`);
                    console.log('Type of clock_in value:', typeof value);
                }
            }
            console.log('=== END FORM DEBUG ===');
            
            const requiredFields = this.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = 'var(--color-danger-500)';
                    isValid = false;
                } else {
                    field.style.borderColor = 'var(--color-border-base)';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                showToast('Please fill in all required fields', 'error');
            }
        });
    }
}

// Make functions globally available
window.showAddModal = showAddModal;
window.hideAddModal = hideAddModal;
window.showEditModal = showEditModal;
window.hideEditModal = hideEditModal;
window.showStartBreakModal = showStartBreakModal;
window.hideStartBreakModal = hideStartBreakModal;
window.showIntervalsModal = showIntervalsModal;
window.hideIntervalsModal = hideIntervalsModal;
window.showReviewAttendanceModal = showReviewAttendanceModal;
window.hideReviewAttendanceModal = hideReviewAttendanceModal;
window.startBreak = startBreak;
window.resumeWork = resumeWork;
window.clockOut = clockOut;
window.autoClose = autoClose;
window.deleteAttendance = deleteAttendance;

console.log('Enhanced Staff attendance JS loaded');
