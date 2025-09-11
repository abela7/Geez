/**
 * Restaurant Settings JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles restaurant operational settings, hours, contact info, and preferences
 */

class RestaurantSettingsManager {
    constructor() {
        this.settings = {};
        this.isDirty = false;
        this.autoSaveTimer = null;
        
        this.init();
    }

    /**
     * Initialize the settings manager
     */
    init() {
        this.bindEvents();
        this.loadSettings();
        this.initializeToggleStates();
        this.setupAutoSave();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Form change tracking
        this.bindFormEvents();
        
        // Logo upload events
        this.bindLogoEvents();
        
        // Operating hours events
        this.bindHoursEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Toggle events
        this.bindToggleEvents();
        
        // Auto-save on input changes
        this.bindAutoSaveEvents();
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const forms = ['general-settings-form', 'hours-settings-form', 'contact-settings-form', 'preferences-settings-form'];
        
        forms.forEach(formId => {
            const form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.saveSettings();
                });
                
                // Track changes
                form.addEventListener('input', () => {
                    this.markDirty();
                });
                
                form.addEventListener('change', () => {
                    this.markDirty();
                });
            }
        });
    }

    /**
     * Bind logo upload events
     */
    bindLogoEvents() {
        const logoFile = document.getElementById('logo-file');
        const uploadBtn = document.querySelector('.upload-logo-btn');
        const removeBtn = document.querySelector('.remove-logo-btn');
        const logoPreview = document.getElementById('logo-preview');

        if (uploadBtn && logoFile) {
            uploadBtn.addEventListener('click', () => {
                logoFile.click();
            });
        }

        if (logoFile) {
            logoFile.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    this.handleLogoUpload(file);
                }
            });
        }

        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                this.removeLogo();
            });
        }

        // Drag and drop for logo
        const logoUploadArea = document.getElementById('logo-upload-area');
        if (logoUploadArea) {
            logoUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                logoUploadArea.classList.add('dragover');
            });

            logoUploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                logoUploadArea.classList.remove('dragover');
            });

            logoUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                logoUploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    this.handleLogoUpload(files[0]);
                }
            });
        }
    }

    /**
     * Bind operating hours events
     */
    bindHoursEvents() {
        // Day toggle events
        const dayToggles = document.querySelectorAll('.toggle-input');
        dayToggles.forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                this.toggleDaySchedule(e.target);
            });
        });

        // Copy hours to all days
        const copyHoursBtn = document.querySelector('.copy-hours-btn');
        if (copyHoursBtn) {
            copyHoursBtn.addEventListener('click', () => {
                this.copyHoursToAll();
            });
        }

        // Reset hours
        const resetHoursBtn = document.querySelector('.reset-hours-btn');
        if (resetHoursBtn) {
            resetHoursBtn.addEventListener('click', () => {
                this.resetHours();
            });
        }
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Save settings button
        const saveBtn = document.querySelector('.save-settings-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                this.saveSettings();
            });
        }

        // Reset defaults button
        const resetBtn = document.querySelector('.reset-defaults-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                this.resetDefaults();
            });
        }
    }

    /**
     * Bind toggle events
     */
    bindToggleEvents() {
        // Update toggle labels based on state
        const toggles = document.querySelectorAll('.toggle-input');
        toggles.forEach(toggle => {
            const updateLabel = () => {
                const label = toggle.parentNode.querySelector('.toggle-label');
                if (label) {
                    label.textContent = toggle.checked ? 'Open' : 'Closed';
                }
            };
            
            toggle.addEventListener('change', updateLabel);
            updateLabel(); // Initial state
        });
    }

    /**
     * Bind auto-save events
     */
    bindAutoSaveEvents() {
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                this.scheduleAutoSave();
            });
            
            input.addEventListener('change', () => {
                this.scheduleAutoSave();
            });
        });
    }

    /**
     * Initialize toggle states
     */
    initializeToggleStates() {
        const toggles = document.querySelectorAll('.toggle-input');
        toggles.forEach(toggle => {
            this.toggleDaySchedule(toggle);
        });
    }

    /**
     * Load settings from storage or defaults
     */
    loadSettings() {
        // Load from localStorage for demo purposes
        const savedSettings = localStorage.getItem('restaurant-settings');
        if (savedSettings) {
            this.settings = JSON.parse(savedSettings);
            this.populateForm();
        } else {
            this.setDefaultSettings();
        }
    }

    /**
     * Set default settings
     */
    setDefaultSettings() {
        this.settings = {
            restaurant_name: 'Geez Restaurant',
            restaurant_tagline: 'Authentic Ethiopian Cuisine',
            restaurant_type: 'ethnic',
            cuisine_type: 'ethiopian',
            seating_capacity: 60,
            price_range: 'moderate',
            default_language: 'en',
            restaurant_description: 'Experience the rich flavors and traditions of Ethiopian cuisine in a warm, welcoming atmosphere.',
            
            // Contact Information
            address: '123 Main Street\nAddis Ababa, Ethiopia',
            phone: '+251-11-123-4567',
            email: 'info@geezrestaurant.com',
            website: 'https://www.geezrestaurant.com',
            social_media: 'https://www.facebook.com/geezrestaurant',
            
            // Location Settings
            timezone: 'Africa/Addis_Ababa',
            currency: 'ETB',
            tax_rate: 15.00,
            service_charge: 10.00,
            
            // Operating Hours
            monday: { enabled: true, open: '09:00', close: '22:00' },
            tuesday: { enabled: true, open: '09:00', close: '22:00' },
            wednesday: { enabled: true, open: '09:00', close: '22:00' },
            thursday: { enabled: true, open: '09:00', close: '22:00' },
            friday: { enabled: true, open: '09:00', close: '23:00' },
            saturday: { enabled: true, open: '09:00', close: '23:00' },
            sunday: { enabled: false, open: '10:00', close: '21:00' },
            
            // Preferences
            max_party_size: 12,
            advance_booking_days: 30,
            reservation_duration: 90,
            allow_walk_ins: true,
            require_confirmation: true,
            auto_confirm: false,
            order_timeout: 30,
            min_order_amount: 50.00,
            allow_special_requests: true,
            require_phone: true,
            
            // Notifications
            notification_email: 'manager@geezrestaurant.com',
            notify_new_reservation: true,
            notify_cancelled_reservation: true,
            notify_no_show: true,
            notify_new_order: true,
            notify_cancelled_order: false,
            notify_low_stock: true,
            notify_expired_items: true,
            notify_new_review: true,
            notify_negative_review: true
        };
        
        this.populateForm();
    }

    /**
     * Populate form with settings data
     */
    populateForm() {
        Object.keys(this.settings).forEach(key => {
            const element = document.querySelector(`[name="${key}"]`);
            if (element) {
                if (element.type === 'checkbox') {
                    element.checked = this.settings[key];
                } else {
                    element.value = this.settings[key];
                }
            }
        });

        // Handle operating hours
        ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
            if (this.settings[day]) {
                const enabledToggle = document.getElementById(`${day}-enabled`);
                const openInput = document.querySelector(`[name="${day}_open"]`);
                const closeInput = document.querySelector(`[name="${day}_close"]`);
                
                if (enabledToggle) {
                    enabledToggle.checked = this.settings[day].enabled;
                    this.toggleDaySchedule(enabledToggle);
                }
                
                if (openInput) openInput.value = this.settings[day].open;
                if (closeInput) closeInput.value = this.settings[day].close;
            }
        });
    }

    /**
     * Handle logo upload
     */
    handleLogoUpload(file) {
        // Validate file type
        const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];
        if (!allowedTypes.includes(file.type)) {
            this.showNotification('Please select a PNG, JPG, or SVG image file', 'error');
            return;
        }

        // Validate file size (max 2MB for optimal performance)
        if (file.size > 2 * 1024 * 1024) {
            this.showNotification('File size must be less than 2MB', 'error');
            return;
        }

        // Create image to check dimensions and resize if needed
        const img = new Image();
        img.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Set target dimensions (200x200 for optimal display)
            const targetSize = 200;
            canvas.width = targetSize;
            canvas.height = targetSize;
            
            // Calculate scaling to maintain aspect ratio
            const scale = Math.min(targetSize / img.width, targetSize / img.height);
            const scaledWidth = img.width * scale;
            const scaledHeight = img.height * scale;
            
            // Center the image on canvas
            const x = (targetSize - scaledWidth) / 2;
            const y = (targetSize - scaledHeight) / 2;
            
            // Clear canvas and draw resized image
            ctx.fillStyle = 'transparent';
            ctx.fillRect(0, 0, targetSize, targetSize);
            ctx.drawImage(img, x, y, scaledWidth, scaledHeight);
            
            // Convert to data URL
            const resizedDataUrl = canvas.toDataURL('image/png', 0.9);
            
            // Update preview
            const logoPreview = document.getElementById('logo-preview');
            const logoDimensions = document.getElementById('logo-dimensions');
            
            if (logoPreview) {
                logoPreview.src = resizedDataUrl;
            }
            
            if (logoDimensions) {
                logoDimensions.textContent = `${targetSize}×${targetSize}px`;
            }
            
            this.settings.logo = resizedDataUrl;
            this.markDirty();
            this.showNotification(`Logo uploaded and resized to ${targetSize}×${targetSize}px`, 'success');
        };
        
        img.onerror = () => {
            this.showNotification('Failed to process image. Please try a different file.', 'error');
        };
        
        // Load the image
        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    /**
     * Remove logo
     */
    removeLogo() {
        if (confirm('Are you sure you want to remove the logo?')) {
            const logoPreview = document.getElementById('logo-preview');
            if (logoPreview) {
                logoPreview.src = '/images/logo-placeholder.svg';
            }
            
            this.settings.logo = null;
            this.markDirty();
            this.showNotification('Logo removed', 'success');
        }
    }

    /**
     * Toggle day schedule
     */
    toggleDaySchedule(toggle) {
        const daySchedule = toggle.closest('.day-schedule');
        const timeInputs = daySchedule.querySelectorAll('.time-input');
        const isEnabled = toggle.checked;
        
        timeInputs.forEach(input => {
            input.disabled = !isEnabled;
        });
        
        // Update visual state
        if (isEnabled) {
            daySchedule.classList.remove('disabled');
        } else {
            daySchedule.classList.add('disabled');
        }
        
        this.markDirty();
    }

    /**
     * Copy hours to all days
     */
    copyHoursToAll() {
        const mondayOpen = document.querySelector('[name="monday_open"]').value;
        const mondayClose = document.querySelector('[name="monday_close"]').value;
        
        if (!mondayOpen || !mondayClose) {
            this.showNotification('Please set Monday hours first', 'warning');
            return;
        }
        
        if (confirm('Copy Monday hours to all other days?')) {
            ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
                const openInput = document.querySelector(`[name="${day}_open"]`);
                const closeInput = document.querySelector(`[name="${day}_close"]`);
                
                if (openInput) openInput.value = mondayOpen;
                if (closeInput) closeInput.value = mondayClose;
            });
            
            this.markDirty();
            this.showNotification('Hours copied to all days', 'success');
        }
    }

    /**
     * Reset hours to defaults
     */
    resetHours() {
        if (confirm('Reset all hours to default schedule?')) {
            const defaultHours = {
                open: '09:00',
                close: '22:00',
                fridayClose: '23:00',
                saturdayClose: '23:00',
                sundayOpen: '10:00',
                sundayClose: '21:00'
            };
            
            ['monday', 'tuesday', 'wednesday', 'thursday'].forEach(day => {
                document.querySelector(`[name="${day}_open"]`).value = defaultHours.open;
                document.querySelector(`[name="${day}_close"]`).value = defaultHours.close;
                document.getElementById(`${day}-enabled`).checked = true;
                this.toggleDaySchedule(document.getElementById(`${day}-enabled`));
            });
            
            // Friday and Saturday (later closing)
            ['friday', 'saturday'].forEach(day => {
                document.querySelector(`[name="${day}_open"]`).value = defaultHours.open;
                document.querySelector(`[name="${day}_close"]`).value = defaultHours.fridayClose;
                document.getElementById(`${day}-enabled`).checked = true;
                this.toggleDaySchedule(document.getElementById(`${day}-enabled`));
            });
            
            // Sunday (different hours)
            document.querySelector('[name="sunday_open"]').value = defaultHours.sundayOpen;
            document.querySelector('[name="sunday_close"]').value = defaultHours.sundayClose;
            document.getElementById('sunday-enabled').checked = false;
            this.toggleDaySchedule(document.getElementById('sunday-enabled'));
            
            this.markDirty();
            this.showNotification('Hours reset to defaults', 'success');
        }
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Save settings
        const saveBtn = document.querySelector('.save-settings-btn');
        if (saveBtn) {
            saveBtn.addEventListener('click', () => {
                this.saveSettings();
            });
        }

        // Reset defaults
        const resetBtn = document.querySelector('.reset-defaults-btn');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                this.resetDefaults();
            });
        }
    }

    /**
     * Bind toggle events
     */
    bindToggleEvents() {
        const toggles = document.querySelectorAll('.toggle-input');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', () => {
                this.updateToggleLabel(toggle);
            });
        });
    }

    /**
     * Bind auto-save events
     */
    bindAutoSaveEvents() {
        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            if (input.type !== 'file') {
                input.addEventListener('blur', () => {
                    this.scheduleAutoSave();
                });
            }
        });
    }

    /**
     * Update toggle label
     */
    updateToggleLabel(toggle) {
        const label = toggle.parentNode.querySelector('.toggle-label');
        if (label) {
            label.textContent = toggle.checked ? 'Open' : 'Closed';
            label.className = `toggle-label ${toggle.checked ? 'text-success' : 'text-danger'}`;
        }
    }

    /**
     * Mark settings as dirty (changed)
     */
    markDirty() {
        this.isDirty = true;
        this.updateSaveButton();
    }

    /**
     * Update save button state
     */
    updateSaveButton() {
        const saveBtn = document.querySelector('.save-settings-btn');
        if (saveBtn) {
            if (this.isDirty) {
                saveBtn.classList.add('btn-warning');
                saveBtn.classList.remove('btn-primary');
                saveBtn.innerHTML = `
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Unsaved Changes
                `;
            } else {
                saveBtn.classList.remove('btn-warning');
                saveBtn.classList.add('btn-primary');
                saveBtn.innerHTML = `
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Settings
                `;
            }
        }
    }

    /**
     * Schedule auto-save
     */
    scheduleAutoSave() {
        if (this.autoSaveTimer) {
            clearTimeout(this.autoSaveTimer);
        }
        
        this.autoSaveTimer = setTimeout(() => {
            this.autoSave();
        }, 2000); // Auto-save after 2 seconds of inactivity
    }

    /**
     * Auto-save settings
     */
    autoSave() {
        if (this.isDirty) {
            this.collectFormData();
            localStorage.setItem('restaurant-settings', JSON.stringify(this.settings));
            console.log('Settings auto-saved');
        }
    }

    /**
     * Setup auto-save
     */
    setupAutoSave() {
        // Auto-save every 30 seconds if dirty
        setInterval(() => {
            if (this.isDirty) {
                this.autoSave();
            }
        }, 30000);
        
        // Save on page unload
        window.addEventListener('beforeunload', (e) => {
            if (this.isDirty) {
                this.autoSave();
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
    }

    /**
     * Collect form data
     */
    collectFormData() {
        const inputs = document.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.name && input.type !== 'file') {
                if (input.type === 'checkbox') {
                    this.settings[input.name] = input.checked;
                } else {
                    this.settings[input.name] = input.value;
                }
            }
        });

        // Collect operating hours
        ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'].forEach(day => {
            const enabled = document.getElementById(`${day}-enabled`)?.checked || false;
            const open = document.querySelector(`[name="${day}_open"]`)?.value || '09:00';
            const close = document.querySelector(`[name="${day}_close"]`)?.value || '22:00';
            
            this.settings[day] = { enabled, open, close };
        });
    }

    /**
     * Save settings
     */
    saveSettings() {
        this.collectFormData();
        
        // Validate required fields
        if (!this.validateSettings()) {
            return;
        }
        
        // Save to localStorage for demo
        localStorage.setItem('restaurant-settings', JSON.stringify(this.settings));
        
        // In real implementation, this would be an API call
        this.simulateApiSave().then(() => {
            this.isDirty = false;
            this.updateSaveButton();
            this.showNotification('Settings saved successfully!', 'success');
        }).catch(() => {
            this.showNotification('Failed to save settings. Please try again.', 'error');
        });
    }

    /**
     * Validate settings
     */
    validateSettings() {
        const requiredFields = ['restaurant_name', 'address', 'phone'];
        
        for (const field of requiredFields) {
            const element = document.querySelector(`[name="${field}"]`);
            if (element && !element.value.trim()) {
                this.showNotification(`Please fill in the ${field.replace('_', ' ')} field`, 'error');
                element.focus();
                return false;
            }
        }
        
        // Validate operating hours
        const hasOpenDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']
            .some(day => document.getElementById(`${day}-enabled`)?.checked);
        
        if (!hasOpenDays) {
            this.showNotification('Restaurant must be open at least one day per week', 'error');
            return false;
        }
        
        return true;
    }

    /**
     * Simulate API save
     */
    simulateApiSave() {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // 95% success rate for demo
                if (Math.random() < 0.95) {
                    resolve();
                } else {
                    reject();
                }
            }, 1000);
        });
    }

    /**
     * Reset to defaults
     */
    resetDefaults() {
        if (confirm('Reset all settings to default values? This action cannot be undone.')) {
            this.setDefaultSettings();
            this.markDirty();
            this.showNotification('Settings reset to defaults', 'info');
        }
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
                <div class="notification-icon">
                    ${this.getNotificationIcon(type)}
                </div>
                <span class="notification-message">${message}</span>
                <button class="notification-close" aria-label="Close">&times;</button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }
        }, 5000);
        
        // Manual close
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }
            });
        }
    }

    /**
     * Get notification icon
     */
    getNotificationIcon(type) {
        const icons = {
            success: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
            error: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
            warning: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            info: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        };
        
        return icons[type] || icons.info;
    }

    /**
     * Get current settings
     */
    getSettings() {
        return { ...this.settings };
    }

    /**
     * Update specific setting
     */
    updateSetting(key, value) {
        this.settings[key] = value;
        this.markDirty();
    }
}

// Notification styles (injected dynamically)
const notificationStyles = `
<style>
.notification {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 9999;
    background: var(--color-bg-primary);
    border: 1px solid var(--color-border);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-xl);
    padding: 1rem;
    min-width: 300px;
    max-width: 400px;
    opacity: 1;
    transition: all 0.3s ease;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-icon {
    width: 1.25rem;
    height: 1.25rem;
    flex-shrink: 0;
}

.notification-success {
    border-left: 4px solid #10b981;
}

.notification-success .notification-icon {
    color: #10b981;
}

.notification-error {
    border-left: 4px solid #ef4444;
}

.notification-error .notification-icon {
    color: #ef4444;
}

.notification-warning {
    border-left: 4px solid #f59e0b;
}

.notification-warning .notification-icon {
    color: #f59e0b;
}

.notification-info {
    border-left: 4px solid #3b82f6;
}

.notification-info .notification-icon {
    color: #3b82f6;
}

.notification-message {
    flex: 1;
    font-size: 0.875rem;
    color: var(--color-text-primary);
}

.notification-close {
    width: 1.5rem;
    height: 1.5rem;
    border: none;
    background: transparent;
    color: var(--color-text-secondary);
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    border-radius: var(--border-radius-sm);
    transition: all 0.2s ease;
}

.notification-close:hover {
    background: var(--color-bg-tertiary);
    color: var(--color-text-primary);
}
</style>
`;

// Inject notification styles
document.head.insertAdjacentHTML('beforeend', notificationStyles);

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.restaurantSettingsManager = new RestaurantSettingsManager();
});
