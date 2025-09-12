/**
 * Cost Analysis Page JavaScript
 * Handles charts, filters, and interactive functionality
 */

// Global variables
let trendsChart = null;
let breakdownChart = null;
let currentChartType = 'line';

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
    setupEventListeners();
    initializeCharts();
});

/**
 * Initialize the page
 */
function initializePage() {
    // Set up form validation
    setupFormValidation();
    
    // Initialize tooltips
    initializeTooltips();
    
    // Set up sortable tables
    setupSortableTable();
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Date range changes
    const startDate = document.getElementById('startDate');
    const endDate = document.getElementById('endDate');
    const periodFilter = document.getElementById('periodFilter');

    if (startDate) startDate.addEventListener('change', updateAnalysis);
    if (endDate) endDate.addEventListener('change', updateAnalysis);
    if (periodFilter) periodFilter.addEventListener('change', updateAnalysis);

    // Modal close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            closeAllModals();
        }
    });

    // Modal close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // Tab switching
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.textContent.toLowerCase().trim();
            switchTab(tabName);
        });
    });
}

/**
 * Initialize charts
 */
function initializeCharts() {
    initializeTrendsChart();
    initializeBreakdownChart();
}

/**
 * Initialize trends chart
 */
function initializeTrendsChart() {
    const canvas = document.getElementById('trendsChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart if it exists
    if (trendsChart) {
        trendsChart.destroy();
    }

    const data = window.trendData || {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            {
                label: 'Total Cost',
                data: [2400, 2800, 2600, 3200, 2900, 3100],
                color: '#ef4444'
            },
            {
                label: 'Revenue',
                data: [3600, 4200, 3900, 4800, 4350, 4650],
                color: '#059669'
            },
            {
                label: 'Profit',
                data: [1200, 1400, 1300, 1600, 1450, 1550],
                color: '#2563eb'
            }
        ]
    };

    // Create simple line chart using canvas
    drawLineChart(ctx, data, canvas.width, canvas.height);
}

/**
 * Initialize breakdown chart
 */
function initializeBreakdownChart() {
    const canvas = document.getElementById('breakdownChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart if it exists
    if (breakdownChart) {
        breakdownChart.destroy();
    }

    const metrics = window.costMetrics || {
        material_cost_percentage: 52.8,
        labor_cost_percentage: 35.2,
        overhead_cost_percentage: 12.0
    };

    const data = [
        { label: 'Materials', value: metrics.material_cost_percentage, color: '#301934' },
        { label: 'Labor', value: metrics.labor_cost_percentage, color: '#D97706' },
        { label: 'Overhead', value: metrics.overhead_cost_percentage, color: '#2563EB' }
    ];

    // Create simple pie chart using canvas
    drawPieChart(ctx, data, canvas.width, canvas.height);
}

/**
 * Draw line chart on canvas
 */
function drawLineChart(ctx, data, width, height) {
    const padding = 60;
    const chartWidth = width - (padding * 2);
    const chartHeight = height - (padding * 2);
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Set styles
    ctx.font = '12px Arial';
    ctx.strokeStyle = '#e5e7eb';
    ctx.lineWidth = 1;
    
    // Draw grid lines
    const gridLines = 5;
    for (let i = 0; i <= gridLines; i++) {
        const y = padding + (chartHeight / gridLines) * i;
        ctx.beginPath();
        ctx.moveTo(padding, y);
        ctx.lineTo(width - padding, y);
        ctx.stroke();
    }
    
    // Find max value for scaling
    const allValues = data.datasets.flatMap(dataset => dataset.data);
    const maxValue = Math.max(...allValues);
    const minValue = Math.min(...allValues);
    const valueRange = maxValue - minValue;
    
    // Draw datasets
    data.datasets.forEach((dataset, datasetIndex) => {
        ctx.strokeStyle = dataset.color;
        ctx.fillStyle = dataset.color;
        ctx.lineWidth = 3;
        
        ctx.beginPath();
        dataset.data.forEach((value, index) => {
            const x = padding + (chartWidth / (dataset.data.length - 1)) * index;
            const y = padding + chartHeight - ((value - minValue) / valueRange) * chartHeight;
            
            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
            
            // Draw data points
            ctx.beginPath();
            ctx.arc(x, y, 4, 0, 2 * Math.PI);
            ctx.fill();
            ctx.beginPath();
        });
        ctx.stroke();
    });
    
    // Draw labels
    ctx.fillStyle = '#6b7280';
    ctx.font = '11px Arial';
    data.labels.forEach((label, index) => {
        const x = padding + (chartWidth / (data.labels.length - 1)) * index;
        ctx.fillText(label, x - 15, height - padding + 20);
    });
}

/**
 * Draw pie chart on canvas
 */
function drawPieChart(ctx, data, width, height) {
    const centerX = width / 2;
    const centerY = height / 2;
    const radius = Math.min(centerX, centerY) - 40;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    let currentAngle = -Math.PI / 2; // Start at top
    const total = data.reduce((sum, item) => sum + item.value, 0);
    
    data.forEach(item => {
        const sliceAngle = (item.value / total) * 2 * Math.PI;
        
        // Draw slice
        ctx.beginPath();
        ctx.moveTo(centerX, centerY);
        ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
        ctx.closePath();
        ctx.fillStyle = item.color;
        ctx.fill();
        
        // Draw border
        ctx.strokeStyle = '#ffffff';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        currentAngle += sliceAngle;
    });
}

/**
 * Switch chart type
 */
function switchChartType(chartName, type) {
    if (chartName === 'trends') {
        currentChartType = type;
        
        // Update button states
        document.querySelectorAll('.chart-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        document.querySelector(`[data-chart="${type}"]`).classList.add('active');
        
        // Reinitialize chart with new type
        initializeTrendsChart();
    }
}

/**
 * Switch tabs in breakdown section
 */
function switchTab(tabName) {
    // Update button states
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Find and activate the correct button
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.textContent.toLowerCase().trim() === tabName) {
            btn.classList.add('active');
        }
    });
    
    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    const targetTab = document.getElementById(`${tabName}-tab`);
    if (targetTab) {
        targetTab.classList.add('active');
    }
}

/**
 * Update analysis based on filters
 */
function updateAnalysis() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const period = document.getElementById('periodFilter').value;
    
    showNotification('Updating cost analysis...', 'info');
    
    // Simulate API call
    setTimeout(() => {
        // Reinitialize charts with new data
        initializeCharts();
        showNotification('Analysis updated successfully!', 'success');
    }, 1000);
}

/**
 * Reset filters
 */
function resetFilters() {
    const today = new Date();
    const thirtyDaysAgo = new Date(today.getTime() - (30 * 24 * 60 * 60 * 1000));
    
    document.getElementById('startDate').value = thirtyDaysAgo.toISOString().split('T')[0];
    document.getElementById('endDate').value = today.toISOString().split('T')[0];
    document.getElementById('periodFilter').value = 'monthly';
    
    updateAnalysis();
}

/**
 * Export analysis
 */
function exportAnalysis() {
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    showNotification('Preparing cost analysis export...', 'info');
    
    // Simulate export process
    setTimeout(() => {
        showNotification('Cost analysis exported successfully!', 'success');
        // In a real implementation, this would trigger a file download
    }, 2000);
}

/**
 * Open settings modal
 */
function openSettingsModal() {
    const modal = document.getElementById('settingsModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

/**
 * Close settings modal
 */
function closeSettingsModal() {
    const modal = document.getElementById('settingsModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }
}

/**
 * Close all modals
 */
function closeAllModals() {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.classList.remove('show');
    });
    document.body.style.overflow = '';
}

/**
 * Save settings
 */
function saveSettings(event) {
    event.preventDefault();
    
    const form = event.target;
    if (!validateForm(form)) return;
    
    const formData = new FormData(form);
    const settings = Object.fromEntries(formData.entries());
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Saving...';
    submitBtn.disabled = true;
    
    // Simulate API call
    setTimeout(() => {
        showNotification('Settings saved successfully!', 'success');
        closeSettingsModal();
        
        // Reset button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Apply settings (in real implementation)
        applySettings(settings);
    }, 1000);
}

/**
 * Apply settings to the interface
 */
function applySettings(settings) {
    // Update currency symbols, decimal places, etc.
    // This would be implemented based on specific requirements
    console.log('Applying settings:', settings);
}

/**
 * Refresh data
 */
function refreshData() {
    showNotification('Refreshing cost analysis data...', 'info');
    
    // Simulate refresh
    setTimeout(() => {
        showNotification('Data refreshed successfully!', 'success');
        initializeCharts();
    }, 1000);
}

/**
 * Setup sortable table
 */
function setupSortableTable() {
    const sortableHeaders = document.querySelectorAll('.sortable');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const sortKey = this.dataset.sort;
            sortTable(sortKey);
        });
    });
}

/**
 * Sort table by column
 */
function sortTable(sortKey) {
    const table = document.querySelector('.data-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('.table-row'));
    
    // Simple sorting implementation
    rows.sort((a, b) => {
        const aValue = getTableCellValue(a, sortKey);
        const bValue = getTableCellValue(b, sortKey);
        
        if (typeof aValue === 'number' && typeof bValue === 'number') {
            return bValue - aValue; // Descending for numbers
        } else {
            return aValue.localeCompare(bValue); // Ascending for strings
        }
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
    
    // Update sort indicators
    updateSortIndicators(sortKey);
}

/**
 * Get table cell value for sorting
 */
function getTableCellValue(row, sortKey) {
    switch (sortKey) {
        case 'batch_name':
            return row.querySelector('.batch-title').textContent.trim();
        case 'volume':
            return parseInt(row.querySelector('.volume-value').textContent.replace(/,/g, ''));
        case 'total_cost':
            return parseFloat(row.querySelector('.cost-value').textContent.replace('$', ''));
        case 'profit':
            return parseFloat(row.querySelector('.profit-value').textContent.replace('$', ''));
        default:
            return '';
    }
}

/**
 * Update sort indicators
 */
function updateSortIndicators(activeKey) {
    document.querySelectorAll('.sortable').forEach(header => {
        const icon = header.querySelector('.sort-icon');
        if (header.dataset.sort === activeKey) {
            icon.style.opacity = '1';
            icon.style.color = 'var(--color-accent)';
        } else {
            icon.style.opacity = '0.5';
            icon.style.color = '';
        }
    });
}

/**
 * Setup form validation
 */
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Validate form
 */
function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            showFieldError(field, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(field);
        }
    });
    
    return isValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#DC2626';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #DC2626;
        font-size: 12px;
        margin-top: 4px;
    `;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(field) {
    field.style.borderColor = '';
    
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[title]');
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', showTooltip);
        element.addEventListener('mouseleave', hideTooltip);
    });
}

/**
 * Show tooltip
 */
function showTooltip(e) {
    const element = e.target;
    const title = element.getAttribute('title');
    
    if (!title) return;
    
    // Remove title to prevent default tooltip
    element.removeAttribute('title');
    element.setAttribute('data-original-title', title);
    
    // Create tooltip element
    const tooltip = document.createElement('div');
    tooltip.className = 'custom-tooltip';
    tooltip.textContent = title;
    tooltip.style.cssText = `
        position: absolute;
        background: #1a1a1a;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        z-index: 1000;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.2s ease;
    `;
    
    document.body.appendChild(tooltip);
    
    // Position tooltip
    const rect = element.getBoundingClientRect();
    tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
    tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    
    // Show tooltip
    setTimeout(() => {
        tooltip.style.opacity = '1';
    }, 10);
    
    element.tooltip = tooltip;
}

/**
 * Hide tooltip
 */
function hideTooltip(e) {
    const element = e.target;
    const originalTitle = element.getAttribute('data-original-title');
    
    if (originalTitle) {
        element.setAttribute('title', originalTitle);
        element.removeAttribute('data-original-title');
    }
    
    if (element.tooltip) {
        element.tooltip.remove();
        element.tooltip = null;
    }
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        opacity: 0;
        transform: translateX(100%);
        transition: all 0.3s ease;
    `;
    
    // Set background color based on type
    const colors = {
        success: '#059669',
        error: '#DC2626',
        warning: '#D97706',
        info: '#2563EB'
    };
    
    notification.style.backgroundColor = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}
