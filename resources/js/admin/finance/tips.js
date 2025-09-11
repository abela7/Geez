/**
 * Tip Management JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles tip collection, distribution calculations, and staff allocation
 */

class TipManagementPage {
    constructor() {
        this.tips = [];
        this.filteredTips = [];
        this.staff = [];
        this.selectedTips = new Set();
        this.currentPeriod = 'today';
        this.distributionRules = {
            current: 'direct',
            direct: { receiver: 100 },
            shared: { frontOfHouse: 60, kitchen: 40 },
            custom: { servers: 45, bartenders: 25, kitchen: 20, management: 10 }
        };
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the tip management page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadTips();
        this.loadStaff();
        this.updateStatistics();
        this.renderTipsTable();
        this.renderStaffDistribution();
        this.setupFormTabs();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Period selector
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const period = e.target.dataset.period;
                this.selectPeriod(period);
            });
        });

        // Filter controls
        const shiftFilter = document.getElementById('shift-filter');
        if (shiftFilter) {
            shiftFilter.addEventListener('change', this.handleFilter.bind(this));
        }

        const statusFilter = document.getElementById('status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', this.handleFilter.bind(this));
        }

        // Main action buttons
        const tipCalculatorBtn = document.querySelector('.tip-calculator-btn');
        if (tipCalculatorBtn) {
            tipCalculatorBtn.addEventListener('click', this.showTipCalculator.bind(this));
        }

        const distributeBtn = document.querySelector('.distribute-tips-btn');
        if (distributeBtn) {
            distributeBtn.addEventListener('click', this.distributePendingTips.bind(this));
        }

        const exportBtn = document.querySelector('.export-tips-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', this.exportTips.bind(this));
        }

        const editRulesBtn = document.querySelector('.edit-rules-btn');
        if (editRulesBtn) {
            editRulesBtn.addEventListener('click', this.showRulesModal.bind(this));
        }

        // Distribution rule cards
        const ruleCards = document.querySelectorAll('.rule-card');
        ruleCards.forEach(card => {
            card.addEventListener('click', (e) => {
                const rule = e.currentTarget.dataset.rule;
                this.selectDistributionRule(rule);
            });
        });

        // Modal functionality
        this.bindModalEvents();

        // Calculator functionality
        this.bindCalculatorEvents();

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Close modal events
        const modalCloses = document.querySelectorAll('.modal-close');
        modalCloses.forEach(btn => {
            btn.addEventListener('click', this.closeModals.bind(this));
        });

        const modalOverlays = document.querySelectorAll('.modal-overlay');
        modalOverlays.forEach(overlay => {
            overlay.addEventListener('click', this.closeModals.bind(this));
        });

        const cancelBtns = document.querySelectorAll('.cancel-btn');
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', this.closeModals.bind(this));
        });
    }

    /**
     * Bind calculator events
     */
    bindCalculatorEvents() {
        // Tip amount input
        const tipAmountInput = document.getElementById('tip-amount');
        if (tipAmountInput) {
            tipAmountInput.addEventListener('input', this.updateDistributionPreview.bind(this));
        }

        // Distribution rule select
        const distributionRuleSelect = document.getElementById('distribution-rule');
        if (distributionRuleSelect) {
            distributionRuleSelect.addEventListener('change', this.updateDistributionPreview.bind(this));
        }

        // Process tip button
        const processTipBtn = document.querySelector('.process-tip-btn');
        if (processTipBtn) {
            processTipBtn.addEventListener('click', this.processTip.bind(this));
        }

        // Rules form
        const rulesForm = document.getElementById('rules-form');
        if (rulesForm) {
            rulesForm.addEventListener('submit', this.saveDistributionRules.bind(this));
        }

        // Percentage inputs for real-time total calculation
        const percentageInputs = document.querySelectorAll('input[name$="_percentage"]');
        percentageInputs.forEach(input => {
            input.addEventListener('input', this.updatePercentageTotal.bind(this));
        });
    }

    /**
     * Setup form tabs functionality
     */
    setupFormTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const targetTab = e.target.dataset.tab;
                
                // Update button states
                tabButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                
                // Update panel visibility
                tabPanels.forEach(panel => {
                    panel.classList.remove('active');
                    if (panel.dataset.tab === targetTab) {
                        panel.classList.add('active');
                    }
                });
            });
        });
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.staff = [
            { id: 1, name: 'Sarah Johnson', role: 'server', avatar: 'SJ', shift: 'evening', active: true },
            { id: 2, name: 'Mike Rodriguez', role: 'bartender', avatar: 'MR', shift: 'evening', active: true },
            { id: 3, name: 'Lisa Chen', role: 'server', avatar: 'LC', shift: 'evening', active: true },
            { id: 4, name: 'James Wilson', role: 'kitchen', avatar: 'JW', shift: 'evening', active: true },
            { id: 5, name: 'Emma Davis', role: 'manager', avatar: 'ED', shift: 'evening', active: true }
        ];

        this.tips = [
            {
                id: 1,
                transactionId: 'TIP-001',
                amount: 25.00,
                paymentMethod: 'cash',
                receivedBy: 1,
                receivedByName: 'Sarah Johnson',
                shift: 'evening',
                time: '2024-01-15 19:30:00',
                status: 'pending',
                distributionRule: 'shared',
                staffOnShift: [1, 2, 3, 4]
            },
            {
                id: 2,
                transactionId: 'TIP-002',
                amount: 15.50,
                paymentMethod: 'card',
                receivedBy: 2,
                receivedByName: 'Mike Rodriguez',
                shift: 'evening',
                time: '2024-01-15 20:15:00',
                status: 'distributed',
                distributionRule: 'direct',
                staffOnShift: [2]
            },
            {
                id: 3,
                transactionId: 'TIP-003',
                amount: 42.75,
                paymentMethod: 'card',
                receivedBy: 3,
                receivedByName: 'Lisa Chen',
                shift: 'evening',
                time: '2024-01-15 21:00:00',
                status: 'pending',
                distributionRule: 'custom',
                staffOnShift: [1, 2, 3, 4, 5]
            }
        ];
    }

    /**
     * Load tips data
     */
    loadTips() {
        this.isLoading = true;
        
        // Simulate loading delay
        setTimeout(() => {
            this.filteredTips = [...this.tips];
            this.renderTipsTable();
            this.isLoading = false;
        }, 500);
    }

    /**
     * Load staff data
     */
    loadStaff() {
        // Staff data already generated
        this.renderStaffGrid();
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const todayTips = this.tips.filter(tip => 
            new Date(tip.time).toDateString() === new Date().toDateString()
        );
        
        const totalTips = todayTips.reduce((sum, tip) => sum + tip.amount, 0);
        const pendingTips = todayTips.filter(tip => tip.status === 'pending');
        const distributedTips = todayTips.filter(tip => tip.status === 'distributed');
        const pendingAmount = pendingTips.reduce((sum, tip) => sum + tip.amount, 0);
        const distributedAmount = distributedTips.reduce((sum, tip) => sum + tip.amount, 0);
        const activeStaff = this.staff.filter(s => s.active).length;
        const avgPerStaff = activeStaff > 0 ? distributedAmount / activeStaff : 0;

        document.getElementById('total-tips').textContent = `$${totalTips.toFixed(2)}`;
        document.getElementById('pending-distribution').textContent = `$${pendingAmount.toFixed(2)}`;
        document.getElementById('distributed-tips').textContent = `$${distributedAmount.toFixed(2)}`;
        document.getElementById('avg-tip-per-staff').textContent = `$${avgPerStaff.toFixed(2)}`;
        
        document.getElementById('pending-count').textContent = `${pendingTips.length} transactions`;
        document.getElementById('staff-count').textContent = `${activeStaff} staff members`;
    }

    /**
     * Render tips table
     */
    renderTipsTable() {
        const tableBody = document.getElementById('tips-table-body');
        if (!tableBody) return;

        tableBody.innerHTML = this.filteredTips.map(tip => `
            <tr data-tip-id="${tip.id}">
                <td class="table-checkbox">
                    <input type="checkbox" class="form-checkbox tip-checkbox" value="${tip.id}" onchange="updateBulkActions()">
                </td>
                <td>${tip.transactionId}</td>
                <td class="tip-amount-cell">$${tip.amount.toFixed(2)}</td>
                <td>${tip.paymentMethod.toUpperCase()}</td>
                <td>${tip.receivedByName}</td>
                <td>${tip.shift}</td>
                <td>${new Date(tip.time).toLocaleTimeString()}</td>
                <td><span class="tip-status ${tip.status}">${tip.status}</span></td>
                <td>
                    <div class="action-buttons">
                        <button class="btn btn-link btn-sm" onclick="tipManager.viewTipDetails(${tip.id})" title="View Details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        ${tip.status === 'pending' ? `
                        <button class="btn btn-link btn-sm" onclick="tipManager.distributeSingleTip(${tip.id})" title="Distribute">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
    }

    /**
     * Render staff distribution summary
     */
    renderStaffDistribution() {
        const container = document.getElementById('staff-distribution-grid');
        if (!container) return;

        const distributedTips = this.tips.filter(tip => tip.status === 'distributed');
        const staffTotals = {};

        // Calculate totals for each staff member
        this.staff.forEach(staff => {
            staffTotals[staff.id] = {
                ...staff,
                totalTips: 0,
                tipCount: 0,
                avgTip: 0
            };
        });

        distributedTips.forEach(tip => {
            const distribution = this.calculateTipDistribution(tip);
            distribution.forEach(dist => {
                if (staffTotals[dist.staffId]) {
                    staffTotals[dist.staffId].totalTips += dist.amount;
                    staffTotals[dist.staffId].tipCount++;
                }
            });
        });

        // Calculate averages
        Object.values(staffTotals).forEach(staff => {
            staff.avgTip = staff.tipCount > 0 ? staff.totalTips / staff.tipCount : 0;
        });

        container.innerHTML = Object.values(staffTotals).map(staff => `
            <div class="staff-distribution-card">
                <div class="staff-header">
                    <div class="staff-info">
                        <div class="staff-avatar">${staff.avatar}</div>
                        <div class="staff-details">
                            <div class="staff-name">${staff.name}</div>
                            <div class="staff-role">${staff.role}</div>
                        </div>
                    </div>
                    <div class="staff-tip-amount">$${staff.totalTips.toFixed(2)}</div>
                </div>
                <div class="staff-metrics">
                    <div class="metric-item">
                        <div class="metric-value">${staff.tipCount}</div>
                        <div class="metric-label">Tips</div>
                    </div>
                    <div class="metric-item">
                        <div class="metric-value">$${staff.avgTip.toFixed(2)}</div>
                        <div class="metric-label">Avg</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render staff grid for calculator
     */
    renderStaffGrid() {
        const container = document.getElementById('staff-grid');
        if (!container) return;

        container.innerHTML = this.staff.map(staff => `
            <div class="staff-checkbox-item" data-staff-id="${staff.id}">
                <input type="checkbox" class="staff-checkbox" value="${staff.id}" ${staff.active ? 'checked' : ''}>
                <div class="staff-info">
                    <div class="staff-name">${staff.name}</div>
                    <div class="staff-role">${staff.role}</div>
                </div>
            </div>
        `).join('');

        // Bind checkbox events
        const checkboxes = container.querySelectorAll('.staff-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', this.updateDistributionPreview.bind(this));
        });
    }

    /**
     * Select distribution rule
     */
    selectDistributionRule(rule) {
        this.distributionRules.current = rule;
        
        // Update UI
        const ruleCards = document.querySelectorAll('.rule-card');
        ruleCards.forEach(card => {
            card.classList.remove('active');
            if (card.dataset.rule === rule) {
                card.classList.add('active');
            }
        });
        
        this.showNotification(`Distribution rule changed to: ${rule}`, 'info');
    }

    /**
     * Show tip calculator modal
     */
    showTipCalculator() {
        const modal = document.getElementById('tip-calculator-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Reset form and update preview
            this.resetCalculatorForm();
            this.updateDistributionPreview();
        }
    }

    /**
     * Show rules modal
     */
    showRulesModal() {
        const modal = document.getElementById('rules-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Populate current rules
            this.populateRulesForm();
        }
    }

    /**
     * Close all modals
     */
    closeModals() {
        const modals = document.querySelectorAll('.tip-calculator-modal, .rules-modal');
        modals.forEach(modal => {
            modal.style.display = 'none';
        });
        document.body.style.overflow = '';
    }

    /**
     * Reset calculator form
     */
    resetCalculatorForm() {
        const form = document.querySelector('#tip-calculator-modal form');
        if (form) {
            form.reset();
            
            // Set default distribution rule
            document.getElementById('distribution-rule').value = this.distributionRules.current;
            
            // Check all active staff
            const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
            staffCheckboxes.forEach(checkbox => {
                const staffId = parseInt(checkbox.value);
                const staff = this.staff.find(s => s.id === staffId);
                checkbox.checked = staff && staff.active;
            });
        }
    }

    /**
     * Update distribution preview
     */
    updateDistributionPreview() {
        const tipAmount = parseFloat(document.getElementById('tip-amount').value) || 0;
        const distributionRule = document.getElementById('distribution-rule').value;
        const selectedStaff = Array.from(document.querySelectorAll('.staff-checkbox:checked')).map(cb => parseInt(cb.value));
        
        if (tipAmount === 0) {
            document.getElementById('distribution-preview').innerHTML = '<p class="text-muted">Enter tip amount to see distribution preview</p>';
            return;
        }

        const distribution = this.calculateTipDistributionPreview(tipAmount, distributionRule, selectedStaff);
        
        const previewHTML = `
            <div class="preview-list">
                ${distribution.map(dist => `
                    <div class="preview-item">
                        <div class="preview-staff">
                            <div class="preview-avatar">${dist.avatar}</div>
                            <div class="preview-name">${dist.name}</div>
                        </div>
                        <div class="preview-amount">$${dist.amount.toFixed(2)}</div>
                    </div>
                `).join('')}
            </div>
        `;
        
        document.getElementById('distribution-preview').innerHTML = previewHTML;
    }

    /**
     * Calculate tip distribution preview
     */
    calculateTipDistributionPreview(amount, rule, selectedStaffIds) {
        const selectedStaff = this.staff.filter(s => selectedStaffIds.includes(s.id));
        const distribution = [];

        switch (rule) {
            case 'direct':
                const receiver = selectedStaff[0];
                if (receiver) {
                    distribution.push({
                        staffId: receiver.id,
                        name: receiver.name,
                        avatar: receiver.avatar,
                        amount: amount
                    });
                }
                break;

            case 'shared':
                const rules = this.distributionRules.shared;
                const fohStaff = selectedStaff.filter(s => ['server', 'bartender', 'manager'].includes(s.role));
                const kitchenStaff = selectedStaff.filter(s => s.role === 'kitchen');
                
                const fohAmount = (amount * rules.frontOfHouse / 100) / fohStaff.length;
                const kitchenAmount = (amount * rules.kitchen / 100) / kitchenStaff.length;
                
                fohStaff.forEach(staff => {
                    distribution.push({
                        staffId: staff.id,
                        name: staff.name,
                        avatar: staff.avatar,
                        amount: fohAmount
                    });
                });
                
                kitchenStaff.forEach(staff => {
                    distribution.push({
                        staffId: staff.id,
                        name: staff.name,
                        avatar: staff.avatar,
                        amount: kitchenAmount
                    });
                });
                break;

            case 'custom':
                const customRules = this.distributionRules.custom;
                const roleGroups = {
                    servers: selectedStaff.filter(s => s.role === 'server'),
                    bartenders: selectedStaff.filter(s => s.role === 'bartender'),
                    kitchen: selectedStaff.filter(s => s.role === 'kitchen'),
                    management: selectedStaff.filter(s => s.role === 'manager')
                };
                
                Object.keys(customRules).forEach(roleKey => {
                    const staffInRole = roleGroups[roleKey] || [];
                    const roleAmount = (amount * customRules[roleKey] / 100) / staffInRole.length;
                    
                    staffInRole.forEach(staff => {
                        distribution.push({
                            staffId: staff.id,
                            name: staff.name,
                            avatar: staff.avatar,
                            amount: roleAmount
                        });
                    });
                });
                break;
        }

        return distribution;
    }

    /**
     * Calculate tip distribution for existing tip
     */
    calculateTipDistribution(tip) {
        const staffOnShift = this.staff.filter(s => tip.staffOnShift.includes(s.id));
        return this.calculateTipDistributionPreview(tip.amount, tip.distributionRule, tip.staffOnShift);
    }

    /**
     * Process new tip
     */
    processTip() {
        const form = document.querySelector('#tip-calculator-modal form');
        const formData = new FormData(form);
        
        const tipAmount = parseFloat(formData.get('tip_amount'));
        const paymentMethod = formData.get('payment_method');
        const receivedBy = parseInt(formData.get('received_by'));
        const distributionRule = formData.get('distribution_rule');
        const selectedStaff = Array.from(document.querySelectorAll('.staff-checkbox:checked')).map(cb => parseInt(cb.value));
        
        if (!tipAmount || tipAmount <= 0) {
            this.showNotification('Please enter a valid tip amount', 'error');
            return;
        }
        
        if (selectedStaff.length === 0) {
            this.showNotification('Please select at least one staff member', 'error');
            return;
        }

        const newTip = {
            id: Date.now(),
            transactionId: `TIP-${String(Date.now()).slice(-3)}`,
            amount: tipAmount,
            paymentMethod: paymentMethod,
            receivedBy: receivedBy,
            receivedByName: this.staff.find(s => s.id === receivedBy)?.name || 'Unknown',
            shift: 'evening',
            time: new Date().toISOString(),
            status: 'pending',
            distributionRule: distributionRule,
            staffOnShift: selectedStaff
        };

        this.tips.push(newTip);
        this.filteredTips = [...this.tips];
        this.renderTipsTable();
        this.updateStatistics();
        this.closeModals();
        
        this.showNotification(`Tip of $${tipAmount.toFixed(2)} recorded successfully`, 'success');
    }

    /**
     * Distribute single tip
     */
    distributeSingleTip(tipId) {
        const tip = this.tips.find(t => t.id === tipId);
        if (!tip) return;

        const distribution = this.calculateTipDistribution(tip);
        
        // Show distribution confirmation
        const distributionText = distribution.map(d => `${d.name}: $${d.amount.toFixed(2)}`).join('\n');
        
        if (confirm(`Distribute tip of $${tip.amount.toFixed(2)}?\n\n${distributionText}`)) {
            tip.status = 'distributed';
            this.renderTipsTable();
            this.renderStaffDistribution();
            this.updateStatistics();
            
            this.showNotification('Tip distributed successfully', 'success');
        }
    }

    /**
     * Distribute pending tips
     */
    distributePendingTips() {
        const pendingTips = this.tips.filter(tip => tip.status === 'pending');
        
        if (pendingTips.length === 0) {
            this.showNotification('No pending tips to distribute', 'info');
            return;
        }

        const totalAmount = pendingTips.reduce((sum, tip) => sum + tip.amount, 0);
        
        if (confirm(`Distribute ${pendingTips.length} pending tips totaling $${totalAmount.toFixed(2)}?`)) {
            pendingTips.forEach(tip => {
                tip.status = 'distributed';
            });
            
            this.renderTipsTable();
            this.renderStaffDistribution();
            this.updateStatistics();
            
            this.showNotification(`${pendingTips.length} tips distributed successfully`, 'success');
        }
    }

    /**
     * Update percentage total in rules form
     */
    updatePercentageTotal() {
        // For shared rules
        const fohPercentage = parseFloat(document.getElementById('foh-percentage')?.value) || 0;
        const kitchenPercentage = parseFloat(document.getElementById('kitchen-percentage')?.value) || 0;
        const sharedTotal = fohPercentage + kitchenPercentage;
        
        const sharedTotalElement = document.getElementById('shared-total');
        if (sharedTotalElement) {
            sharedTotalElement.textContent = `${sharedTotal}%`;
            sharedTotalElement.style.color = sharedTotal === 100 ? 'var(--color-tips-success)' : 'var(--color-tips-danger)';
        }

        // For custom rules
        const customInputs = document.querySelectorAll('input[name$="_percentage"]');
        let customTotal = 0;
        customInputs.forEach(input => {
            customTotal += parseFloat(input.value) || 0;
        });
        
        const customTotalElement = document.getElementById('custom-total');
        if (customTotalElement) {
            customTotalElement.textContent = `${customTotal}%`;
            customTotalElement.style.color = customTotal === 100 ? 'var(--color-tips-success)' : 'var(--color-tips-danger)';
        }
    }

    /**
     * Save distribution rules
     */
    saveDistributionRules(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        
        // Update rules
        this.distributionRules.shared = {
            frontOfHouse: parseFloat(formData.get('foh_percentage')),
            kitchen: parseFloat(formData.get('kitchen_percentage'))
        };
        
        this.distributionRules.custom = {
            servers: parseFloat(formData.get('servers_percentage')),
            bartenders: parseFloat(formData.get('bartenders_percentage')),
            kitchen: parseFloat(formData.get('kitchen_percentage')),
            management: parseFloat(formData.get('management_percentage'))
        };
        
        this.closeModals();
        this.showNotification('Distribution rules saved successfully', 'success');
    }

    /**
     * Populate rules form
     */
    populateRulesForm() {
        const shared = this.distributionRules.shared;
        const custom = this.distributionRules.custom;
        
        document.getElementById('foh-percentage').value = shared.frontOfHouse;
        document.getElementById('kitchen-percentage').value = shared.kitchen;
        
        document.querySelector('input[name="servers_percentage"]').value = custom.servers;
        document.querySelector('input[name="bartenders_percentage"]').value = custom.bartenders;
        document.querySelector('input[name="kitchen_percentage"]').value = custom.kitchen;
        document.querySelector('input[name="management_percentage"]').value = custom.management;
        
        this.updatePercentageTotal();
    }

    /**
     * Handle filter changes
     */
    handleFilter() {
        const shiftFilter = document.getElementById('shift-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        
        this.filteredTips = this.tips.filter(tip => {
            if (shiftFilter && tip.shift !== shiftFilter) return false;
            if (statusFilter && tip.status !== statusFilter) return false;
            return true;
        });
        
        this.renderTipsTable();
    }

    /**
     * Select time period
     */
    selectPeriod(period) {
        this.currentPeriod = period;
        
        // Update button states
        const periodButtons = document.querySelectorAll('.period-btn');
        periodButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.period === period) {
                btn.classList.add('active');
            }
        });
        
        // Update data based on period
        this.filterByPeriod(period);
    }

    /**
     * Filter tips by period
     */
    filterByPeriod(period) {
        const now = new Date();
        let startDate;
        
        switch (period) {
            case 'today':
                startDate = new Date(now.getFullYear(), now.getMonth(), now.getDate());
                break;
            case 'week':
                startDate = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                break;
            case 'month':
                startDate = new Date(now.getFullYear(), now.getMonth(), 1);
                break;
            default:
                startDate = new Date(0); // Show all
        }
        
        this.filteredTips = this.tips.filter(tip => new Date(tip.time) >= startDate);
        this.renderTipsTable();
        this.updateStatistics();
    }

    /**
     * Export tips data
     */
    exportTips() {
        this.showNotification('Exporting tips data...', 'info');
        
        setTimeout(() => {
            this.showNotification('Tips data exported successfully', 'success');
        }, 1500);
    }

    /**
     * Show notification
     */
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span>${message}</span>
                <button type="button" class="notification-close" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 5000);
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (event.key) {
                case 't':
                    event.preventDefault();
                    this.showTipCalculator();
                    break;
                case 'd':
                    event.preventDefault();
                    this.distributePendingTips();
                    break;
                case 'r':
                    event.preventDefault();
                    this.showRulesModal();
                    break;
                case 'e':
                    event.preventDefault();
                    this.exportTips();
                    break;
            }
        }
        
        if (event.key === 'Escape') {
            this.closeModals();
        }
    }
}

// Global functions for HTML onclick handlers
function toggleSelectAll() {
    // Implementation for bulk selection
}

function updateBulkActions() {
    // Implementation for bulk actions
}

// Initialize the page when DOM is loaded
let tipManager;

document.addEventListener('DOMContentLoaded', function() {
    tipManager = new TipManagementPage();
});
