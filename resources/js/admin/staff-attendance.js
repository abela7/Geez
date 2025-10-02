/**
 * Staff Attendance - Admin View
 * Handles modal interactions for creating/editing attendance records
 */

// Modal state management
let addModal = null;
let editModal = null;
let currentAttendanceId = null;

/**
 * Show the add attendance modal
 */
function showAddModal() {
    addModal = document.getElementById('addAttendanceModal');
    if (addModal) {
        addModal.classList.remove('hidden');
        addModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Hide the add attendance modal
 */
function hideAddModal() {
    if (addModal) {
        addModal.classList.add('hidden');
        addModal.classList.remove('flex');
        document.body.style.overflow = '';
        // Reset form
        document.getElementById('addAttendanceForm')?.reset();
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
    }
});

/**
 * Close modal on Escape key
 */
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideAddModal();
        hideEditModal();
    }
});

/**
 * Auto-submit filters on change
 */
document.addEventListener('DOMContentLoaded', function() {
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
});

// Make functions globally available
window.showAddModal = showAddModal;
window.hideAddModal = hideAddModal;
window.showEditModal = showEditModal;
window.hideEditModal = hideEditModal;
window.deleteAttendance = deleteAttendance;

console.log('Staff attendance JS loaded');
