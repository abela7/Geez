import './bootstrap';

// Defer Alpine's start until Livewire initializes to avoid multiple instances
// This MUST be defined BEFORE importing Alpine
window.deferLoadingAlpine = function(callback) {
  window.addEventListener('livewire:init', callback);
};

// Import Alpine.js
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import { shiftCreateComponent } from './admin/shifts/create-component.js';

// Expose Alpine globally so Livewire detects it and doesn't inject its own
window.Alpine = Alpine;

// Register plugins and components before Alpine starts
Alpine.plugin(persist);
Alpine.data('shiftCreateData', shiftCreateComponent);

// Register shifts manage component
Alpine.data('shiftsManageData', () => ({
    // Filters
    searchQuery: '',
    filterDepartment: 'all',
    filterStatus: 'all',
    filterType: 'all',
    
    // Sorting
    sortField: 'name',
    sortDirection: 'asc',
    
    // Selection
    selectAll: false,
    selectedShifts: [],
    showBulkActions: false,
    
    // Initialize
    init() {
        console.log('Shifts manage component initialized');
    },
    
    // Basic functionality
    applyFilters() {
        console.log('Applying filters');
    },
    
    resetFilters() {
        this.searchQuery = '';
        this.filterDepartment = 'all';
        this.filterStatus = 'all';
        this.filterType = 'all';
    },
    
    toggleSelectAll() {
        console.log('Toggle select all');
    }
}));

// Register shifts edit component (similar to create but for editing)
Alpine.data('shiftEditData', (initialData) => ({
    // Form data
    form: {
        name: initialData?.name || '',
        department: initialData?.department || '',
        type: initialData?.type || '',
        description: initialData?.description || '',
        start_time: initialData?.start_time || '',
        end_time: initialData?.end_time || '',
        break_duration: initialData?.break_duration || 30,
        days_of_week: initialData?.days_of_week || [],
        required_staff: initialData?.required_staff || 1,
        hourly_rate: initialData?.hourly_rate || '',
        overtime_rate: initialData?.overtime_rate || '',
        status: initialData?.status || 'draft',
        duration_hours: 0
    },

    // UI state
    isSubmitting: false,

    // Initialize component
    init() {
        this.calculateDuration();
        
        // Watch specifically for time changes
        this.$watch('form.start_time', () => {
            this.calculateDuration();
        });
        this.$watch('form.end_time', () => {
            this.calculateDuration();
        });

        console.log('Shift Edit Form initialized');
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

    // Calculate costs
    calculateDailyCost() {
        if (!this.form.hourly_rate || !this.form.required_staff || !this.form.duration_hours) {
            return 0;
        }
        
        const workHours = this.form.duration_hours - (this.form.break_duration / 60 || 0);
        return this.form.hourly_rate * this.form.required_staff * workHours;
    },

    calculateWeeklyCost() {
        const dailyCost = this.calculateDailyCost();
        return dailyCost * this.form.days_of_week.length;
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
    }
}));

// Start Alpine (Livewire will trigger the deferred start)
Alpine.start();
