/**
 * Injera Management JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles injera production cycle, sales analysis, and intelligent recommendations
 */

class InjeraManagementPage {
    constructor() {
        this.productionBatches = [];
        this.salesData = [];
        this.inventory = {};
        this.currentTab = 'production';
        this.wizardStep = 1;
        this.recommendations = {};
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialize the injera management page
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.loadProductionData();
        this.updateStatistics();
        this.generateRecommendations();
        this.setupTabs();
        this.setupProductionWizard();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Tab navigation
        const tabButtons = document.querySelectorAll('.tab-btn');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const tab = e.currentTarget.dataset.tab;
                this.switchTab(tab);
            });
        });

        // Main action buttons
        const newBatchBtn = document.querySelector('.new-batch-btn');
        if (newBatchBtn) {
            newBatchBtn.addEventListener('click', this.showProductionModal.bind(this));
        }

        const analyticsBtn = document.querySelector('.analytics-btn');
        if (analyticsBtn) {
            analyticsBtn.addEventListener('click', this.showAnalytics.bind(this));
        }

        const recommendationBtn = document.querySelector('.recommendation-btn');
        if (recommendationBtn) {
            recommendationBtn.addEventListener('click', this.showRecommendations.bind(this));
        }

        // Production step buttons
        this.bindProductionStepEvents();

        // Modal functionality
        this.bindModalEvents();

        // Wizard functionality
        this.bindWizardEvents();

        // Recommendation actions
        this.bindRecommendationEvents();

        // Real-time calculations
        this.bindCalculationEvents();

        // Keyboard shortcuts
        document.addEventListener('keydown', this.handleKeyboardShortcuts.bind(this));
    }

    /**
     * Bind production step events
     */
    bindProductionStepEvents() {
        const stepButtons = document.querySelectorAll('.step-card button');
        stepButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const action = e.target.className;
                const stepCard = e.target.closest('.step-card');
                const step = stepCard.dataset.step;
                this.handleProductionStep(step, action);
            });
        });
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Close modal events
        const modalClose = document.querySelector('.modal-close');
        if (modalClose) {
            modalClose.addEventListener('click', this.closeProductionModal.bind(this));
        }

        const modalOverlay = document.querySelector('.modal-overlay');
        if (modalOverlay) {
            modalOverlay.addEventListener('click', this.closeProductionModal.bind(this));
        }

        // Production form
        const productionForm = document.getElementById('production-form');
        if (productionForm) {
            productionForm.addEventListener('submit', this.handleProductionSubmit.bind(this));
        }
    }

    /**
     * Bind wizard events
     */
    bindWizardEvents() {
        const nextBtn = document.querySelector('.next-step-btn');
        if (nextBtn) {
            nextBtn.addEventListener('click', this.nextWizardStep.bind(this));
        }

        const prevBtn = document.querySelector('.prev-step-btn');
        if (prevBtn) {
            prevBtn.addEventListener('click', this.prevWizardStep.bind(this));
        }
    }

    /**
     * Bind recommendation events
     */
    bindRecommendationEvents() {
        const acceptBtn = document.querySelector('.accept-recommendation-btn');
        if (acceptBtn) {
            acceptBtn.addEventListener('click', this.acceptProductionRecommendation.bind(this));
        }

        const enableSalesBtn = document.querySelector('.enable-sales-btn');
        if (enableSalesBtn) {
            enableSalesBtn.addEventListener('click', this.enableInjeraSales.bind(this));
        }

        const implementStrategyBtn = document.querySelector('.implement-strategy-btn');
        if (implementStrategyBtn) {
            implementStrategyBtn.addEventListener('click', this.implementWasteReduction.bind(this));
        }
    }

    /**
     * Bind calculation events
     */
    bindCalculationEvents() {
        const ingredientInputs = document.querySelectorAll('#teff-amount, #wheat-amount, #water-amount');
        ingredientInputs.forEach(input => {
            input.addEventListener('input', this.calculateExpectedYield.bind(this));
        });

        const salesPeriodSelect = document.getElementById('sales-period');
        if (salesPeriodSelect) {
            salesPeriodSelect.addEventListener('change', this.updateSalesMetrics.bind(this));
        }
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.productionBatches = [
            {
                id: 1,
                batchId: 'INJ-001',
                status: 'baking',
                currentStep: 4,
                teffAmount: 10,
                wheatAmount: 2,
                waterAmount: 8,
                expectedYield: 45,
                actualYield: null,
                startDate: '2024-01-13T08:00:00',
                mixingDate: '2024-01-13T08:30:00',
                waterAdditionDate: '2024-01-16T10:00:00',
                bakingDate: '2024-01-16T14:00:00',
                completionDate: null,
                baker: 'Almaz Tadesse',
                cost: 90.60
            },
            {
                id: 2,
                batchId: 'INJ-002',
                status: 'fermentation',
                currentStep: 3,
                teffAmount: 8,
                wheatAmount: 1.5,
                waterAmount: 6,
                expectedYield: 35,
                actualYield: null,
                startDate: '2024-01-14T09:00:00',
                mixingDate: '2024-01-14T09:30:00',
                waterAdditionDate: null,
                bakingDate: null,
                completionDate: null,
                baker: null,
                cost: 72.30
            }
        ];

        this.inventory = {
            teffFlour: { amount: 25.5, costPerKg: 8.50, lastPurchase: '2024-01-12', reorderLevel: 10 },
            wheatFlour: { amount: 3.2, costPerKg: 2.80, lastPurchase: '2024-01-10', reorderLevel: 5 },
            water: { amount: 50, costPerKg: 0.01, lastPurchase: '2024-01-15', reorderLevel: 20 }
        };

        this.salesData = {
            today: {
                foodService: 28,
                directSales: 12,
                waste: 3,
                totalProduced: 43,
                revenue: 36.00,
                wasteCost: 6.03
            },
            historical: [
                { date: '2024-01-08', foodService: 25, directSales: 8, waste: 2 },
                { date: '2024-01-09', foodService: 30, directSales: 15, waste: 4 },
                { date: '2024-01-10', foodService: 22, directSales: 10, waste: 1 },
                { date: '2024-01-11', foodService: 35, directSales: 18, waste: 5 },
                { date: '2024-01-12', foodService: 28, directSales: 12, waste: 3 }
            ]
        };
    }

    /**
     * Load production data
     */
    loadProductionData() {
        this.isLoading = true;
        
        // Simulate loading delay
        setTimeout(() => {
            this.renderProductionTimeline();
            this.renderCurrentBatches();
            this.isLoading = false;
        }, 500);
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const today = this.salesData.today;
        const yesterday = this.salesData.historical[this.salesData.historical.length - 1];
        
        // Calculate efficiency (injeras per kg of flour)
        const totalFlourUsed = this.productionBatches.reduce((sum, batch) => 
            sum + batch.teffAmount + batch.wheatAmount, 0
        );
        const totalProduced = this.productionBatches.reduce((sum, batch) => 
            sum + (batch.actualYield || batch.expectedYield), 0
        );
        const efficiency = totalFlourUsed > 0 ? (totalProduced / totalFlourUsed).toFixed(1) : 0;

        // Calculate remaining injera and estimated hours
        const consumptionRate = (today.foodService + today.directSales) / 12; // per hour
        const estimatedHours = consumptionRate > 0 ? Math.round(today.totalProduced / consumptionRate) : 0;

        // Update DOM elements
        document.getElementById('daily-production').textContent = today.totalProduced;
        document.getElementById('injera-remaining').textContent = today.totalProduced - today.foodService - today.directSales - today.waste;
        document.getElementById('flour-efficiency').textContent = efficiency;
        document.getElementById('estimated-hours').textContent = `~${estimatedHours}h remaining`;

        // Calculate trends
        const productionTrend = yesterday ? 
            ((today.totalProduced - yesterday.foodService - yesterday.directSales) / (yesterday.foodService + yesterday.directSales) * 100).toFixed(1) : 0;
        
        const trendElement = document.getElementById('production-trend');
        if (trendElement) {
            trendElement.textContent = `${productionTrend > 0 ? '+' : ''}${productionTrend}% vs yesterday`;
            trendElement.className = `stat-change ${productionTrend >= 0 ? 'positive' : 'negative'}`;
        }

        // Update recommendation status
        const recommendation = this.calculateSellingRecommendation();
        document.getElementById('recommendation-status').textContent = recommendation.status;
        document.getElementById('recommendation-reason').textContent = recommendation.reason;
    }

    /**
     * Calculate selling recommendation based on analysis
     */
    calculateSellingRecommendation() {
        const today = this.salesData.today;
        const historical = this.salesData.historical;
        
        // Factors to consider:
        // 1. Current stock level
        // 2. Historical demand patterns
        // 3. Day of week comparison
        // 4. Waste rate
        // 5. Production capacity
        
        const stockLevel = today.totalProduced - today.foodService - today.directSales - today.waste;
        const avgDemand = historical.reduce((sum, day) => sum + day.directSales, 0) / historical.length;
        const wasteRate = (today.waste / today.totalProduced) * 100;
        
        let score = 0;
        let reasons = [];
        
        // Stock level factor
        if (stockLevel > avgDemand * 2) {
            score += 30;
            reasons.push('High stock level');
        } else if (stockLevel > avgDemand) {
            score += 20;
            reasons.push('Adequate stock');
        } else {
            score += 5;
            reasons.push('Low stock');
        }
        
        // Demand trend factor
        const recentDemand = historical.slice(-3).reduce((sum, day) => sum + day.directSales, 0) / 3;
        if (recentDemand > avgDemand * 1.1) {
            score += 25;
            reasons.push('Increasing demand');
        } else if (recentDemand >= avgDemand * 0.9) {
            score += 15;
            reasons.push('Stable demand');
        } else {
            score += 5;
            reasons.push('Decreasing demand');
        }
        
        // Waste rate factor
        if (wasteRate < 5) {
            score += 20;
            reasons.push('Low waste rate');
        } else if (wasteRate < 10) {
            score += 10;
            reasons.push('Moderate waste');
        } else {
            score -= 10;
            reasons.push('High waste risk');
        }
        
        // Day of week factor (assuming Friday is good for injera sales)
        const dayOfWeek = new Date().getDay();
        if ([5, 6, 0].includes(dayOfWeek)) { // Friday, Saturday, Sunday
            score += 15;
            reasons.push('Good sales day');
        }
        
        // Determine recommendation
        if (score >= 70) {
            return { status: 'GOOD', reason: reasons.slice(0, 2).join(', '), confidence: 85 + score - 70 };
        } else if (score >= 50) {
            return { status: 'CAUTION', reason: reasons.slice(0, 2).join(', '), confidence: 60 + score - 50 };
        } else {
            return { status: 'AVOID', reason: reasons.slice(0, 2).join(', '), confidence: Math.max(30, score) };
        }
    }

    /**
     * Setup tabs functionality
     */
    setupTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabPanels = document.querySelectorAll('.tab-panel');

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const targetTab = e.target.closest('.tab-btn').dataset.tab;
                
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
                
                this.currentTab = targetTab;
                this.loadTabContent(targetTab);
            });
        });
    }

    /**
     * Setup production wizard
     */
    setupProductionWizard() {
        this.updateWizardStep(1);
        this.calculateExpectedYield();
    }

    /**
     * Switch tab and load content
     */
    switchTab(tab) {
        this.currentTab = tab;
        
        switch (tab) {
            case 'production':
                this.renderProductionTimeline();
                break;
            case 'inventory':
                this.renderInventoryStatus();
                this.renderCurrentBatches();
                break;
            case 'sales':
                this.renderSalesMetrics();
                this.renderSalesChart();
                break;
            case 'recommendations':
                this.renderRecommendations();
                break;
        }
    }

    /**
     * Load tab content
     */
    loadTabContent(tab) {
        // Load specific content based on tab
        console.log(`Loading content for tab: ${tab}`);
    }

    /**
     * Render production timeline
     */
    renderProductionTimeline() {
        const container = document.getElementById('production-timeline');
        if (!container) return;

        container.innerHTML = this.productionBatches.map(batch => `
            <div class="timeline-batch" data-batch-id="${batch.id}">
                <div class="batch-header">
                    <div class="batch-id">${batch.batchId}</div>
                    <div class="batch-status ${batch.status}">${batch.status}</div>
                </div>
                <div class="batch-progress">
                    <div class="progress-step ${batch.currentStep >= 1 ? 'completed' : ''} ${batch.currentStep === 1 ? 'current' : ''}">
                        <svg class="progress-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <div class="progress-label">Ingredients</div>
                        <div class="progress-time">${batch.startDate ? new Date(batch.startDate).toLocaleDateString() : 'Pending'}</div>
                    </div>
                    
                    <div class="progress-step ${batch.currentStep >= 2 ? 'completed' : ''} ${batch.currentStep === 2 ? 'current' : ''}">
                        <svg class="progress-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                        </svg>
                        <div class="progress-label">Mixing</div>
                        <div class="progress-time">${batch.mixingDate ? new Date(batch.mixingDate).toLocaleDateString() : 'Pending'}</div>
                    </div>
                    
                    <div class="progress-step ${batch.currentStep >= 3 ? 'completed' : ''} ${batch.currentStep === 3 ? 'current' : ''}">
                        <svg class="progress-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="progress-label">Fermentation</div>
                        <div class="progress-time">${this.calculateFermentationTime(batch)}</div>
                    </div>
                    
                    <div class="progress-step ${batch.currentStep >= 4 ? 'completed' : ''} ${batch.currentStep === 4 ? 'current' : ''}">
                        <svg class="progress-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/>
                        </svg>
                        <div class="progress-label">Baking</div>
                        <div class="progress-time">${batch.bakingDate ? new Date(batch.bakingDate).toLocaleDateString() : 'Scheduled'}</div>
                    </div>
                </div>
                <div class="batch-details">
                    <div class="batch-info">
                        <span class="info-label">Flour:</span>
                        <span class="info-value">${batch.teffAmount + batch.wheatAmount} kg</span>
                    </div>
                    <div class="batch-info">
                        <span class="info-label">Expected Yield:</span>
                        <span class="info-value">${batch.expectedYield} injeras</span>
                    </div>
                    <div class="batch-info">
                        <span class="info-label">Cost:</span>
                        <span class="info-value">$${batch.cost.toFixed(2)}</span>
                    </div>
                    ${batch.baker ? `
                    <div class="batch-info">
                        <span class="info-label">Baker:</span>
                        <span class="info-value">${batch.baker}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        `).join('');
    }

    /**
     * Calculate fermentation time remaining
     */
    calculateFermentationTime(batch) {
        if (!batch.mixingDate) return 'Pending';
        
        const mixingDate = new Date(batch.mixingDate);
        const now = new Date();
        const hoursElapsed = (now - mixingDate) / (1000 * 60 * 60);
        const fermentationHours = 72; // 3 days
        const remainingHours = Math.max(0, fermentationHours - hoursElapsed);
        
        if (remainingHours === 0) return 'Ready';
        if (remainingHours < 24) return `${Math.round(remainingHours)}h left`;
        return `${Math.round(remainingHours / 24)}d left`;
    }

    /**
     * Render sales metrics
     */
    renderSalesMetrics() {
        const today = this.salesData.today;
        const efficiency = ((today.foodService + today.directSales) / today.totalProduced * 100).toFixed(1);
        
        document.getElementById('food-service-injera').textContent = today.foodService;
        document.getElementById('direct-sales-injera').textContent = today.directSales;
        document.getElementById('waste-injera').textContent = today.waste;
        document.getElementById('efficiency-rate').textContent = `${efficiency}%`;
        
        document.getElementById('food-service-percentage').textContent = `${(today.foodService / today.totalProduced * 100).toFixed(1)}%`;
        document.getElementById('direct-sales-revenue').textContent = today.revenue.toFixed(2);
        document.getElementById('waste-cost').textContent = today.wasteCost.toFixed(2);
    }

    /**
     * Generate intelligent recommendations
     */
    generateRecommendations() {
        const recommendation = this.calculateSellingRecommendation();
        this.recommendations = {
            selling: recommendation,
            production: this.calculateProductionRecommendation(),
            wasteReduction: this.calculateWasteReductionRecommendation()
        };
    }

    /**
     * Calculate production recommendation
     */
    calculateProductionRecommendation() {
        const avgDemand = this.salesData.historical.reduce((sum, day) => 
            sum + day.foodService + day.directSales, 0
        ) / this.salesData.historical.length;
        
        const recommendedBatchSize = Math.ceil(avgDemand * 1.2); // 20% buffer
        const flourNeeded = recommendedBatchSize / 4; // Assuming 4 injeras per kg
        
        return {
            confidence: 95,
            batchSize: flourNeeded,
            expectedYield: recommendedBatchSize,
            startTime: 'Tomorrow 8:00 AM',
            reason: 'Based on historical demand patterns and current stock levels'
        };
    }

    /**
     * Calculate waste reduction recommendation
     */
    calculateWasteReductionRecommendation() {
        const wasteRate = (this.salesData.today.waste / this.salesData.today.totalProduced) * 100;
        const targetWasteRate = 5;
        const currentWasteCost = this.salesData.today.wasteCost;
        const potentialSavings = currentWasteCost * (wasteRate - targetWasteRate) / wasteRate;
        
        return {
            confidence: 92,
            currentWasteRate: wasteRate.toFixed(1),
            targetWasteRate: targetWasteRate,
            potentialSavings: potentialSavings.toFixed(2),
            strategies: [
                'Adjust production timing based on demand patterns',
                'Implement dynamic pricing for end-of-day sales',
                'Improve storage conditions to extend freshness'
            ]
        };
    }

    /**
     * Show production modal
     */
    showProductionModal() {
        const modal = document.getElementById('production-modal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            
            // Reset wizard to step 1
            this.updateWizardStep(1);
            this.calculateExpectedYield();
        }
    }

    /**
     * Close production modal
     */
    closeProductionModal() {
        const modal = document.getElementById('production-modal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }

    /**
     * Update wizard step
     */
    updateWizardStep(step) {
        this.wizardStep = step;
        
        // Update step indicators
        const wizardSteps = document.querySelectorAll('.wizard-step');
        wizardSteps.forEach((stepEl, index) => {
            stepEl.classList.remove('active');
            if (index + 1 === step) {
                stepEl.classList.add('active');
            }
        });
        
        // Update panels
        const wizardPanels = document.querySelectorAll('.wizard-panel');
        wizardPanels.forEach((panel, index) => {
            panel.classList.remove('active');
            if (index + 1 === step) {
                panel.classList.add('active');
            }
        });
        
        // Update navigation buttons
        const prevBtn = document.querySelector('.prev-step-btn');
        const nextBtn = document.querySelector('.next-step-btn');
        const startBtn = document.querySelector('.start-production-btn');
        
        if (prevBtn) prevBtn.style.display = step > 1 ? 'block' : 'none';
        if (nextBtn) nextBtn.style.display = step < 4 ? 'block' : 'none';
        if (startBtn) startBtn.style.display = step === 4 ? 'block' : 'none';
        
        // Auto-calculate dates for subsequent steps
        if (step > 1) {
            this.calculateScheduleDates();
        }
    }

    /**
     * Next wizard step
     */
    nextWizardStep() {
        if (this.wizardStep < 4) {
            this.updateWizardStep(this.wizardStep + 1);
        }
    }

    /**
     * Previous wizard step
     */
    prevWizardStep() {
        if (this.wizardStep > 1) {
            this.updateWizardStep(this.wizardStep - 1);
        }
    }

    /**
     * Calculate expected yield based on flour amounts
     */
    calculateExpectedYield() {
        const teffAmount = parseFloat(document.getElementById('teff-amount')?.value) || 0;
        const wheatAmount = parseFloat(document.getElementById('wheat-amount')?.value) || 0;
        const totalFlour = teffAmount + wheatAmount;
        
        // Assuming 4-4.5 injeras per kg of flour
        const minYield = Math.floor(totalFlour * 4);
        const maxYield = Math.floor(totalFlour * 4.5);
        
        const yieldElement = document.getElementById('expected-yield');
        if (yieldElement) {
            yieldElement.textContent = `${minYield}-${maxYield}`;
        }
        
        // Update cost calculation
        const teffCost = teffAmount * this.inventory.teffFlour.costPerKg;
        const wheatCost = wheatAmount * this.inventory.wheatFlour.costPerKg;
        const totalCost = teffCost + wheatCost;
        const costPerInjera = totalCost / ((minYield + maxYield) / 2);
        
        // Update summary if visible
        const totalFlourSummary = document.getElementById('total-flour-summary');
        if (totalFlourSummary) {
            totalFlourSummary.textContent = `${totalFlour} kg`;
        }
        
        const totalCostSummary = document.getElementById('total-cost-summary');
        if (totalCostSummary) {
            totalCostSummary.textContent = `$${totalCost.toFixed(2)}`;
        }
        
        const costPerInjeraSummary = document.getElementById('cost-per-injera');
        if (costPerInjeraSummary) {
            costPerInjeraSummary.textContent = `$${costPerInjera.toFixed(2)}`;
        }
    }

    /**
     * Calculate schedule dates
     */
    calculateScheduleDates() {
        const mixingDate = document.getElementById('mixing-date')?.value;
        if (!mixingDate) return;
        
        const mixing = new Date(mixingDate);
        const fermentationDays = parseInt(document.getElementById('fermentation-days')?.value) || 3;
        
        // Calculate water addition date (after fermentation)
        const waterDate = new Date(mixing.getTime() + fermentationDays * 24 * 60 * 60 * 1000);
        const waterInput = document.getElementById('water-addition-date');
        if (waterInput) {
            waterInput.value = waterDate.toISOString().slice(0, 16);
        }
        
        // Calculate baking date (2-4 hours after water addition)
        const bakingDate = new Date(waterDate.getTime() + 4 * 60 * 60 * 1000);
        const bakingInput = document.getElementById('baking-start-date');
        if (bakingInput) {
            bakingInput.value = bakingDate.toISOString().slice(0, 16);
        }
        
        // Update completion date in summary
        const completionDate = document.getElementById('completion-date');
        if (completionDate) {
            completionDate.textContent = bakingDate.toLocaleDateString() + ', ' + bakingDate.toLocaleTimeString();
        }
    }

    /**
     * Handle production step actions
     */
    handleProductionStep(step, action) {
        switch (step) {
            case '1':
                if (action.includes('record-purchase')) {
                    this.recordIngredientPurchase();
                }
                break;
            case '2':
                if (action.includes('start-mixing')) {
                    this.startMixingProcess();
                }
                break;
            case '3':
                if (action.includes('add-water')) {
                    this.addHotWater();
                }
                break;
            case '4':
                if (action.includes('start-baking')) {
                    this.startBaking();
                }
                break;
        }
    }

    /**
     * Record ingredient purchase
     */
    recordIngredientPurchase() {
        this.showNotification('Ingredient purchase recorded', 'success');
    }

    /**
     * Start mixing process
     */
    startMixingProcess() {
        this.showNotification('Mixing process started', 'success');
    }

    /**
     * Add hot water
     */
    addHotWater() {
        this.showNotification('Hot water addition recorded', 'success');
    }

    /**
     * Start baking
     */
    startBaking() {
        this.showNotification('Baking process started', 'success');
    }

    /**
     * Accept production recommendation
     */
    acceptProductionRecommendation() {
        const recommendation = this.recommendations.production;
        
        // Pre-fill production form with recommended values
        document.getElementById('teff-amount').value = recommendation.batchSize * 0.8; // 80% teff
        document.getElementById('wheat-amount').value = recommendation.batchSize * 0.2; // 20% wheat
        document.getElementById('target-quantity').value = recommendation.expectedYield;
        
        this.showProductionModal();
        this.showNotification('Recommendation applied to production form', 'success');
    }

    /**
     * Enable injera sales
     */
    enableInjeraSales() {
        this.showNotification('Injera sales enabled based on analysis', 'success');
    }

    /**
     * Implement waste reduction strategy
     */
    implementWasteReduction() {
        this.showNotification('Waste reduction strategy implemented', 'success');
    }

    /**
     * Handle production form submission
     */
    handleProductionSubmit(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        
        const newBatch = {
            id: Date.now(),
            batchId: `INJ-${String(Date.now()).slice(-3)}`,
            status: 'scheduled',
            currentStep: 1,
            teffAmount: parseFloat(formData.get('teff_amount')),
            wheatAmount: parseFloat(formData.get('wheat_amount')),
            waterAmount: parseFloat(formData.get('water_amount')),
            expectedYield: parseInt(formData.get('target_quantity')),
            startDate: formData.get('mixing_date'),
            baker: formData.get('baker_assigned'),
            cost: this.calculateBatchCost(formData)
        };
        
        this.productionBatches.push(newBatch);
        this.renderProductionTimeline();
        this.closeProductionModal();
        
        this.showNotification(`Production batch ${newBatch.batchId} scheduled successfully`, 'success');
    }

    /**
     * Calculate batch cost
     */
    calculateBatchCost(formData) {
        const teffAmount = parseFloat(formData.get('teff_amount'));
        const wheatAmount = parseFloat(formData.get('wheat_amount'));
        
        const teffCost = teffAmount * this.inventory.teffFlour.costPerKg;
        const wheatCost = wheatAmount * this.inventory.wheatFlour.costPerKg;
        
        return teffCost + wheatCost;
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
                case 'n':
                    event.preventDefault();
                    this.showProductionModal();
                    break;
                case 'r':
                    event.preventDefault();
                    this.generateRecommendations();
                    this.showNotification('Recommendations refreshed', 'info');
                    break;
                case '1':
                    event.preventDefault();
                    this.switchTab('production');
                    break;
                case '2':
                    event.preventDefault();
                    this.switchTab('inventory');
                    break;
                case '3':
                    event.preventDefault();
                    this.switchTab('sales');
                    break;
                case '4':
                    event.preventDefault();
                    this.switchTab('recommendations');
                    break;
            }
        }
        
        if (event.key === 'Escape') {
            this.closeProductionModal();
        }
    }
}

// Initialize the page when DOM is loaded
let injeraManager;

document.addEventListener('DOMContentLoaded', function() {
    injeraManager = new InjeraManagementPage();
});
