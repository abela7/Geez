/**
 * Shift Create Form Component
 * Exported as a function for Alpine.data() registration
 */

export function shiftCreateComponent() {
    return {
        // Form data
        form: {
            name: '',
            position_name: '',
            department: '',
            type: '',
            description: '',
            start_time: '',
            end_time: '',
            break_duration: 30,
            required_staff: 1,
            hourly_rate: '',
            overtime_rate: '',
            status: 'draft',
            duration_hours: 0
        },

        // UI state
        currentStep: 1,
        isSubmitting: false,
        showCostCalculation: false,

        // Initialize component
        init() {
            this.loadFromLocalStorage();
            this.updateProgressSteps();
            this.calculateDuration();
            
            // Auto-save form data to localStorage
            this.$watch('form', () => {
                this.saveToLocalStorage();
                this.updateCostVisibility();
            }, { deep: true });

            // Watch specifically for time changes
            this.$watch('form.start_time', () => {
                this.calculateDuration();
            });
            this.$watch('form.end_time', () => {
                this.calculateDuration();
            });

            // Update progress steps based on form completion
            this.$watch('form', () => {
                this.updateProgressSteps();
            }, { deep: true });

            console.log('Shift Create Form initialized');
        },

        // Form validation
        isFormValid() {
            const requiredFields = [
                'name',
                'department', 
                'type',
                'start_time',
                'end_time',
                'required_staff',
                'status'
            ];

            const hasRequiredFields = requiredFields.every(field => {
                const value = this.form[field];
                return value !== '' && value !== null && value !== undefined;
            });

            const hasDaysSelected = true; // Templates don't need days
            const hasValidTimes = this.form.start_time && this.form.end_time && this.form.duration_hours > 0;
            const hasValidStaff = this.form.required_staff > 0;

            return hasRequiredFields && hasDaysSelected && hasValidTimes && hasValidStaff;
        },

        // Validate individual sections
        isSection1Valid() {
            return this.form.name && this.form.department && this.form.type;
        },

        isSection2Valid() {
            return this.form.start_time && this.form.end_time && 
                   this.form.duration_hours > 0;
        },

        isSection3Valid() {
            return this.form.required_staff > 0 && this.form.status;
        },

        // Update progress steps visual state
        updateProgressSteps() {
            const steps = document.querySelectorAll('.progress-step');
            
            steps.forEach((step, index) => {
                const stepNumber = index + 1;
                step.classList.remove('active', 'completed');
                
                if (stepNumber < this.currentStep) {
                    step.classList.add('completed');
                } else if (stepNumber === this.currentStep) {
                    step.classList.add('active');
                }

                // Mark steps as completed based on validation
                if (stepNumber === 1 && this.isSection1Valid()) {
                    step.classList.add('completed');
                }
                if (stepNumber === 2 && this.isSection2Valid()) {
                    step.classList.add('completed');
                }
                if (stepNumber === 3 && this.isSection3Valid()) {
                    step.classList.add('completed');
                }
                if (stepNumber === 4 && this.isFormValid()) {
                    step.classList.add('completed');
                }
            });
        },

        // Calculate shift duration
        calculateDuration() {
            if (!this.form.start_time || !this.form.end_time) {
                this.form.duration_hours = 0;
                return;
            }

            try {
                const start = new Date(`2000-01-01T${this.form.start_time}:00`);
                const end = new Date(`2000-01-01T${this.form.end_time}:00`);
                
                // Handle overnight shifts
                if (end < start) {
                    end.setDate(end.getDate() + 1);
                }
                
                const diffMs = end - start;
                const diffHours = diffMs / (1000 * 60 * 60);
                
                this.form.duration_hours = Math.round(diffHours * 100) / 100; // Round to 2 decimal places
            } catch (error) {
                console.error('Error calculating duration:', error);
                this.form.duration_hours = 0;
            }
        },

        // Format duration for display
        formatDuration(hours) {
            if (!hours || hours === 0) {
                return 'Select start and end times';
            }
            
            const wholeHours = Math.floor(hours);
            const minutes = Math.round((hours - wholeHours) * 60);
            
            if (minutes === 0) {
                return `${wholeHours} hour${wholeHours !== 1 ? 's' : ''}`;
            } else {
                return `${wholeHours}h ${minutes}m`;
            }
        },

        // Update cost calculation visibility
        updateCostVisibility() {
            this.showCostCalculation = this.form.required_staff > 0 && this.form.hourly_rate > 0;
        },

        // Calculate costs
        calculateCosts() {
            this.updateCostVisibility();
        },

        calculateDailyCost() {
            if (!this.form.hourly_rate || !this.form.required_staff || !this.form.duration_hours) {
                return 0;
            }
            
            const workHours = this.form.duration_hours - (this.form.break_duration / 60 || 0);
            return this.form.hourly_rate * this.form.required_staff * workHours;
        },

        calculateWeeklyCost() {
            const dailyCost = this.calculateDailyCost();
            return dailyCost * 7; // Estimate for 7-day week
        },

        calculateMonthlyCost() {
            const weeklyCost = this.calculateWeeklyCost();
            return weeklyCost * 4.33; // Average weeks per month
        },

        // Format currency
        formatCurrency(amount) {
            if (!amount || isNaN(amount)) {
                return '$0.00';
            }
            
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(amount);
        },

        // Form actions
        resetForm() {
            if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
                this.form = {
                    name: '',
                    department: '',
                    type: '',
                    description: '',
                    start_time: '',
                    end_time: '',
                    break_duration: 30,
                    position_name: '',
                    required_staff: 1,
                    hourly_rate: '',
                    overtime_rate: '',
                    status: 'draft',
                    duration_hours: 0
                };
                
                this.currentStep = 1;
                this.clearLocalStorage();
                this.updateProgressSteps();
                
                // Show success message
                this.showNotification('Form reset successfully', 'success');
            }
        },

        saveDraft() {
            if (!this.isFormValid()) {
                this.showNotification('Please fill in all required fields before saving', 'error');
                return;
            }

            // Set status to draft
            this.form.status = 'draft';
            
            // Submit form
            this.submitForm();
        },

        submitForm() {
            if (!this.isFormValid()) {
                this.showNotification('Please fill in all required fields', 'error');
                return;
            }

            this.isSubmitting = true;
            
            // Add loading class to form
            const form = document.querySelector('.shift-form');
            if (form) {
                form.classList.add('form-loading');
            }

            // Show submitting notification
            this.showNotification('Creating shift...', 'info');

            // Submit the form via AJAX to get immediate feedback
            this.submitFormAjax(form);
        },

        async submitFormAjax(form) {
            try {
                const formData = new FormData(form);
                
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Success - show notification and redirect
                    this.showNotification(`Shift "${this.form.name}" created successfully!`, 'success');
                    this.clearLocalStorage();
                    
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = result.redirect || '/admin/shifts/manage';
                    }, 1500);
                } else {
                    // Handle validation errors or other issues
                    const errorMessage = result.message || 'Failed to create shift. Please check your input.';
                    this.showNotification(errorMessage, 'error');
                    
                    // Show specific field errors if available
                    if (result.errors) {
                        Object.keys(result.errors).forEach(field => {
                            const errorMsg = result.errors[field][0];
                            this.showNotification(`${field}: ${errorMsg}`, 'error');
                        });
                    }
                }
            } catch (error) {
                console.error('Form submission error:', error);
                this.showNotification('Network error. Please try again.', 'error');
            } finally {
                this.isSubmitting = false;
                const form = document.querySelector('.shift-form');
                if (form) {
                    form.classList.remove('form-loading');
                }
            }
        },

        // Local storage management
        saveToLocalStorage() {
            try {
                localStorage.setItem('shift_create_form', JSON.stringify(this.form));
            } catch (error) {
                console.error('Error saving to localStorage:', error);
            }
        },

        loadFromLocalStorage() {
            try {
                const saved = localStorage.getItem('shift_create_form');
                if (saved) {
                    const parsedData = JSON.parse(saved);
                    this.form = { ...this.form, ...parsedData };
                }
            } catch (error) {
                console.error('Error loading from localStorage:', error);
            }
        },

        clearLocalStorage() {
            try {
                localStorage.removeItem('shift_create_form');
            } catch (error) {
                console.error('Error clearing localStorage:', error);
            }
        },

        // Notification system
        showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.innerHTML = `
                <div class="notification-content">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
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
        },

        // Step navigation (for future multi-step implementation)
        goToStep(step) {
            if (step >= 1 && step <= 4) {
                this.currentStep = step;
                this.updateProgressSteps();
                
                // Scroll to the corresponding section
                const section = document.querySelector(`[data-section="${step}"]`);
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        },

        nextStep() {
            if (this.currentStep < 4) {
                this.currentStep++;
                this.goToStep(this.currentStep);
            }
        },

        previousStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                this.goToStep(this.currentStep);
            }
        },

        // Utility methods
        formatTime(time) {
            if (!time) return '';
            
            try {
                const [hours, minutes] = time.split(':');
                const date = new Date();
                date.setHours(parseInt(hours), parseInt(minutes));
                
                return date.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: '2-digit',
                    hour12: true
                });
            } catch (error) {
                return time;
            }
        },

        getDayName(day) {
            const days = {
                'monday': 'Monday',
                'tuesday': 'Tuesday', 
                'wednesday': 'Wednesday',
                'thursday': 'Thursday',
                'friday': 'Friday',
                'saturday': 'Saturday',
                'sunday': 'Sunday'
            };
            return days[day] || day;
        },

        // Form field helpers
        updateField(field, value) {
            this.form[field] = value;
        },

        toggleDay(day) {
            // Days of week selector removed - templates don't need specific days
            console.log('Day toggle removed - use assignments page to set specific days');
        }
    };
}

