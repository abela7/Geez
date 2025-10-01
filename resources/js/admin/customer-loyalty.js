/**
 * Customer Loyalty Program JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles loyalty program management, points tracking, rewards, and member tiers
 */

class LoyaltyProgramManager {
    constructor() {
        this.members = [];
        this.rewards = [];
        this.tiers = [];
        this.transactions = [];
        this.filteredMembers = [];
        this.searchTerm = '';
        this.filters = {
            tier: '',
            status: '',
            transactionType: '',
            dateFrom: '',
            dateTo: ''
        };
        this.currentMember = null;
        this.currentReward = null;
        this.isEditing = false;
        
        this.init();
    }

    /**
     * Initialize the loyalty program manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderMembers();
        this.renderRewards();
        this.renderTiers();
        this.renderTransactions();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // Tab events
        this.bindTabEvents();
        
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
        const membersSearch = document.getElementById('members-search');
        const tierFilter = document.getElementById('tier-filter');
        const statusFilter = document.getElementById('status-filter');
        const transactionTypeFilter = document.getElementById('transaction-type-filter');
        const dateFromFilter = document.getElementById('date-from');
        const dateToFilter = document.getElementById('date-to');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (membersSearch) {
            membersSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderMembers();
            });
        }

        if (tierFilter) {
            tierFilter.addEventListener('change', (e) => {
                this.filters.tier = e.target.value;
                this.filterAndRenderMembers();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.filterAndRenderMembers();
            });
        }

        if (transactionTypeFilter) {
            transactionTypeFilter.addEventListener('change', (e) => {
                this.filters.transactionType = e.target.value;
                this.renderTransactions();
            });
        }

        if (dateFromFilter) {
            dateFromFilter.addEventListener('change', (e) => {
                this.filters.dateFrom = e.target.value;
                this.renderTransactions();
            });
        }

        if (dateToFilter) {
            dateToFilter.addEventListener('change', (e) => {
                this.filters.dateTo = e.target.value;
                this.renderTransactions();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind tab events
     */
    bindTabEvents() {
        // Tab switching is handled by Alpine.js
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Reward modal
        this.bindModalCloseEvents('reward-modal', () => this.closeRewardModal());
        
        // Member details modal
        this.bindModalCloseEvents('member-details-modal', () => this.closeMemberDetails());
        
        // Points adjustment modal
        this.bindModalCloseEvents('points-modal', () => this.closePointsModal());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeRewardModal();
                this.closeMemberDetails();
                this.closePointsModal();
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
        const cancelBtn = modal.querySelector('.cancel-reward-btn, .close-member-details-btn, .cancel-points-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add reward buttons
        document.querySelectorAll('.add-reward-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openRewardModal());
        });

        // Program settings
        const settingsBtn = document.querySelector('.loyalty-settings-btn');
        if (settingsBtn) {
            settingsBtn.addEventListener('click', () => this.openProgramSettings());
        }

        // Export data
        const exportBtn = document.querySelector('.export-loyalty-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportLoyaltyData());
        }

        // Edit tiers
        const editTiersBtn = document.querySelector('.edit-tiers-btn');
        if (editTiersBtn) {
            editTiersBtn.addEventListener('click', () => this.editTiers());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Member card click
            if (e.target.closest('.member-card')) {
                const memberId = parseInt(e.target.closest('.member-card').dataset.memberId);
                this.openMemberDetails(memberId);
            }
            
            // Points adjustment button
            if (e.target.closest('.action-btn.points')) {
                e.stopPropagation();
                const memberId = parseInt(e.target.closest('[data-member-id]').dataset.memberId);
                this.openPointsModal(memberId);
            }
            
            // Reward edit button
            if (e.target.closest('.reward-edit-btn')) {
                e.stopPropagation();
                const rewardId = parseInt(e.target.closest('[data-reward-id]').dataset.rewardId);
                this.editReward(rewardId);
            }
            
            // Reward delete button
            if (e.target.closest('.reward-delete-btn')) {
                e.stopPropagation();
                const rewardId = parseInt(e.target.closest('[data-reward-id]').dataset.rewardId);
                this.deleteReward(rewardId);
            }
            
            // Adjust points from member details
            if (e.target.closest('.adjust-points-btn')) {
                const memberId = this.currentMember?.id;
                if (memberId) {
                    this.closeMemberDetails();
                    this.openPointsModal(memberId);
                }
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const rewardForm = document.getElementById('reward-form');
        if (rewardForm) {
            rewardForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveReward();
            });
        }

        const pointsForm = document.getElementById('points-form');
        if (pointsForm) {
            pointsForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.savePointsAdjustment();
            });
        }
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.generateTiers();
        this.generateRewards();
        this.generateMembers();
        this.generateTransactions();
    }

    /**
     * Generate membership tiers
     */
    generateTiers() {
        this.tiers = [
            {
                id: 1,
                name: 'Bronze',
                key: 'bronze',
                minPoints: 0,
                maxPoints: 499,
                benefits: [
                    'Earn 1 point per £1 spent',
                    'Birthday discount 10%',
                    'Member-only promotions'
                ],
                members: 0,
                color: '#cd7f32'
            },
            {
                id: 2,
                name: 'Silver',
                key: 'silver',
                minPoints: 500,
                maxPoints: 1499,
                benefits: [
                    'Earn 1.5 points per £1 spent',
                    'Birthday discount 15%',
                    'Priority reservations',
                    'Free appetizer monthly'
                ],
                members: 0,
                color: '#c0c0c0'
            },
            {
                id: 3,
                name: 'Gold',
                key: 'gold',
                minPoints: 1500,
                maxPoints: 2999,
                benefits: [
                    'Earn 2 points per £1 spent',
                    'Birthday discount 20%',
                    'Priority reservations',
                    'Free dessert monthly',
                    'Complimentary valet parking'
                ],
                members: 0,
                color: '#ffd700'
            },
            {
                id: 4,
                name: 'Platinum',
                key: 'platinum',
                minPoints: 3000,
                maxPoints: null,
                benefits: [
                    'Earn 3 points per £1 spent',
                    'Birthday discount 25%',
                    'VIP reservations',
                    'Free meal monthly',
                    'Complimentary valet parking',
                    'Personal concierge service'
                ],
                members: 0,
                color: '#e5e4e2'
            }
        ];
    }

    /**
     * Generate rewards
     */
    generateRewards() {
        this.rewards = [
            {
                id: 1,
                name: 'Free Appetizer',
                type: 'free_item',
                pointsRequired: 100,
                value: '£8.99 value',
                description: 'Choose any appetizer from our menu, complimentary with your meal.',
                expiryDays: 30,
                status: 'active',
                timesRedeemed: 45
            },
            {
                id: 2,
                name: '10% Off Meal',
                type: 'discount',
                pointsRequired: 150,
                value: '10% discount',
                description: 'Get 10% off your entire meal, excluding alcohol.',
                expiryDays: 30,
                status: 'active',
                timesRedeemed: 32
            },
            {
                id: 3,
                name: 'Free Dessert',
                type: 'free_item',
                pointsRequired: 75,
                value: '£6.99 value',
                description: 'Enjoy any dessert from our selection, on the house.',
                expiryDays: 30,
                status: 'active',
                timesRedeemed: 28
            },
            {
                id: 4,
                name: '£5 Cashback',
                type: 'cashback',
                pointsRequired: 250,
                value: '£5.00',
                description: 'Receive £5 credit towards your next visit.',
                expiryDays: 60,
                status: 'active',
                timesRedeemed: 18
            },
            {
                id: 5,
                name: 'VIP Table Upgrade',
                type: 'upgrade',
                pointsRequired: 200,
                value: 'Premium seating',
                description: 'Upgrade to our best available table with priority service.',
                expiryDays: 30,
                status: 'active',
                timesRedeemed: 12
            },
            {
                id: 6,
                name: 'Free Main Course',
                type: 'free_item',
                pointsRequired: 400,
                value: '£18.99 value',
                description: 'Choose any main course from our menu, completely free.',
                expiryDays: 30,
                status: 'inactive',
                timesRedeemed: 8
            }
        ];
    }

    /**
     * Generate loyalty members
     */
    generateMembers() {
        const firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria', 'William', 'Jennifer', 'Richard', 'Patricia', 'Charles', 'Linda'];
        const lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson'];
        const domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com'];
        
        this.members = [];
        
        for (let i = 1; i <= 40; i++) {
            const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
            const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
            const email = `${firstName.toLowerCase()}.${lastName.toLowerCase()}@${domains[Math.floor(Math.random() * domains.length)]}`;
            
            // Generate points and determine tier
            const points = Math.floor(Math.random() * 4000);
            const tier = this.getTierByPoints(points);
            
            // Generate visit and spending data
            const visits = Math.floor(Math.random() * 25) + 1;
            const totalSpent = visits * (Math.random() * 60 + 20); // £20-80 per visit
            const lastVisit = new Date();
            lastVisit.setDate(lastVisit.getDate() - Math.floor(Math.random() * 90));
            
            // Determine status based on last visit
            const daysSinceLastVisit = Math.floor((new Date() - lastVisit) / (1000 * 60 * 60 * 24));
            const status = daysSinceLastVisit > 60 ? 'inactive' : 'active';
            
            const member = {
                id: i,
                firstName: firstName,
                lastName: lastName,
                email: email,
                phone: `+44 ${Math.floor(Math.random() * 9000) + 1000} ${Math.floor(Math.random() * 900000) + 100000}`,
                points: points,
                tier: tier.key,
                tierName: tier.name,
                visits: visits,
                totalSpent: totalSpent,
                lastVisit: lastVisit,
                status: status,
                joinedDate: this.generateRandomDate(new Date(2022, 0, 1), new Date()),
                pointsEarned: points + Math.floor(Math.random() * 500),
                pointsRedeemed: Math.floor(Math.random() * 300),
                rewardsRedeemed: Math.floor(Math.random() * 8)
            };
            
            this.members.push(member);
        }
        
        // Update tier member counts
        this.tiers.forEach(tier => {
            tier.members = this.members.filter(m => m.tier === tier.key).length;
        });
        
        // Sort by points (highest first)
        this.members.sort((a, b) => b.points - a.points);
    }

    /**
     * Generate transactions
     */
    generateTransactions() {
        this.transactions = [];
        let transactionId = 1;
        
        this.members.forEach(member => {
            // Generate earned points transactions
            const earnedTransactions = Math.floor(Math.random() * 10) + 5;
            for (let i = 0; i < earnedTransactions; i++) {
                const date = this.generateRandomDate(member.joinedDate, new Date());
                const points = Math.floor(Math.random() * 50) + 10;
                const amount = points; // £1 = 1 point
                
                this.transactions.push({
                    id: transactionId++,
                    memberId: member.id,
                    memberName: `${member.firstName} ${member.lastName}`,
                    type: 'earned',
                    points: points,
                    description: `Earned from £${amount.toFixed(2)} purchase`,
                    date: date,
                    balance: 0 // Will be calculated
                });
            }
            
            // Generate redeemed points transactions
            const redeemedTransactions = Math.floor(Math.random() * 3) + 1;
            for (let i = 0; i < redeemedTransactions; i++) {
                const date = this.generateRandomDate(member.joinedDate, new Date());
                const reward = this.rewards[Math.floor(Math.random() * this.rewards.length)];
                
                this.transactions.push({
                    id: transactionId++,
                    memberId: member.id,
                    memberName: `${member.firstName} ${member.lastName}`,
                    type: 'redeemed',
                    points: -reward.pointsRequired,
                    description: `Redeemed: ${reward.name}`,
                    date: date,
                    balance: 0 // Will be calculated
                });
            }
        });
        
        // Sort by date (most recent first)
        this.transactions.sort((a, b) => b.date - a.date);
        
        // Calculate running balances
        this.calculateTransactionBalances();
    }

    /**
     * Calculate transaction balances
     */
    calculateTransactionBalances() {
        const memberBalances = {};
        
        // Initialize member balances
        this.members.forEach(member => {
            memberBalances[member.id] = 0;
        });
        
        // Sort transactions by date (oldest first) for balance calculation
        const sortedTransactions = [...this.transactions].sort((a, b) => a.date - b.date);
        
        sortedTransactions.forEach(transaction => {
            memberBalances[transaction.memberId] += transaction.points;
            transaction.balance = memberBalances[transaction.memberId];
        });
    }

    /**
     * Get tier by points
     */
    getTierByPoints(points) {
        for (let i = this.tiers.length - 1; i >= 0; i--) {
            const tier = this.tiers[i];
            if (points >= tier.minPoints && (tier.maxPoints === null || points <= tier.maxPoints)) {
                return tier;
            }
        }
        return this.tiers[0]; // Default to Bronze
    }

    /**
     * Generate random date between two dates
     */
    generateRandomDate(start, end) {
        return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const totalMembers = this.members.length;
        const activeMembers = this.members.filter(m => m.status === 'active').length;
        const pointsIssued = this.members.reduce((sum, m) => sum + m.pointsEarned, 0);
        const rewardsRedeemed = this.members.reduce((sum, m) => sum + m.rewardsRedeemed, 0);

        document.getElementById('total-members').textContent = totalMembers;
        document.getElementById('active-members').textContent = activeMembers;
        document.getElementById('points-issued').textContent = pointsIssued.toLocaleString();
        document.getElementById('rewards-redeemed').textContent = rewardsRedeemed;
    }

    /**
     * Filter and render members
     */
    filterAndRenderMembers() {
        this.filteredMembers = this.members.filter(member => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                member.firstName.toLowerCase().includes(this.searchTerm) ||
                member.lastName.toLowerCase().includes(this.searchTerm) ||
                member.email.toLowerCase().includes(this.searchTerm);

            // Tier filter
            const tierMatch = !this.filters.tier || member.tier === this.filters.tier;

            // Status filter
            const statusMatch = !this.filters.status || member.status === this.filters.status;

            return searchMatch && tierMatch && statusMatch;
        });

        this.renderMembers();
    }

    /**
     * Render members
     */
    renderMembers() {
        const membersList = document.getElementById('members-list');
        if (!membersList) return;

        const membersToShow = this.filteredMembers.length ? this.filteredMembers : this.members;

        membersList.innerHTML = membersToShow.map(member => `
            <div class="member-card" data-member-id="${member.id}">
                <div class="member-card-header">
                    <div class="member-avatar">
                        ${this.getMemberInitials(member)}
                    </div>
                    <div class="member-info">
                        <div class="member-name">${member.firstName} ${member.lastName}</div>
                        <div class="member-email">${member.email}</div>
                    </div>
                </div>
                <div class="member-tier">
                    <span class="tier-badge ${member.tier}">${member.tierName}</span>
                </div>
                <div class="member-stats">
                    <div class="member-stat">
                        <div class="stat-label">Points</div>
                        <div class="stat-value points-value">${member.points}</div>
                    </div>
                    <div class="member-stat">
                        <div class="stat-label">Visits</div>
                        <div class="stat-value">${member.visits}</div>
                    </div>
                    <div class="member-stat">
                        <div class="stat-label">Total Spent</div>
                        <div class="stat-value">£${member.totalSpent.toFixed(2)}</div>
                    </div>
                    <div class="member-stat">
                        <div class="stat-label">Last Visit</div>
                        <div class="stat-value">${this.formatDate(member.lastVisit)}</div>
                    </div>
                </div>
                <div class="member-actions">
                    <div class="member-status">
                        <div class="status-indicator ${member.status}"></div>
                        <span>${this.formatStatus(member.status)}</span>
                    </div>
                    <div class="member-action-buttons">
                        <button class="action-btn points" title="Adjust Points">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render rewards
     */
    renderRewards() {
        const rewardsGrid = document.getElementById('rewards-grid');
        if (!rewardsGrid) return;

        rewardsGrid.innerHTML = this.rewards.map(reward => `
            <div class="reward-card" data-reward-id="${reward.id}">
                <div class="reward-header">
                    <div class="reward-info">
                        <div class="reward-name">${reward.name}</div>
                        <div class="reward-type">${this.formatRewardType(reward.type)}</div>
                    </div>
                    <div class="reward-points">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        ${reward.pointsRequired}
                    </div>
                </div>
                <div class="reward-description">${reward.description}</div>
                <div class="reward-details">
                    <div class="reward-value">${reward.value}</div>
                    <span class="reward-status ${reward.status}">${this.formatStatus(reward.status)}</span>
                </div>
                <div class="reward-actions">
                    <button class="action-btn reward-edit-btn" title="Edit Reward">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    <button class="action-btn reward-delete-btn" title="Delete Reward">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render tiers
     */
    renderTiers() {
        const tiersGrid = document.getElementById('tiers-grid');
        if (!tiersGrid) return;

        tiersGrid.innerHTML = this.tiers.map(tier => `
            <div class="tier-card ${tier.key}">
                <div class="tier-header">
                    <div class="tier-icon ${tier.key}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div class="tier-info">
                        <div class="tier-name">${tier.name}</div>
                        <div class="tier-requirement">
                            ${tier.minPoints}${tier.maxPoints ? ` - ${tier.maxPoints}` : '+'} points
                        </div>
                    </div>
                </div>
                <div class="tier-benefits">
                    <div class="tier-benefits-title">Benefits</div>
                    <ul class="tier-benefits-list">
                        ${tier.benefits.map(benefit => `<li>${benefit}</li>`).join('')}
                    </ul>
                </div>
                <div class="tier-stats">
                    <div class="tier-stat">
                        <div class="tier-stat-value">${tier.members}</div>
                        <div class="tier-stat-label">Members</div>
                    </div>
                    <div class="tier-stat">
                        <div class="tier-stat-value">${Math.round((tier.members / this.members.length) * 100)}%</div>
                        <div class="tier-stat-label">Of Total</div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    /**
     * Render transactions
     */
    renderTransactions() {
        const tableBody = document.getElementById('transactions-table-body');
        if (!tableBody) return;

        let filteredTransactions = [...this.transactions];

        // Apply filters
        if (this.filters.transactionType) {
            filteredTransactions = filteredTransactions.filter(t => t.type === this.filters.transactionType);
        }

        if (this.filters.dateFrom) {
            const fromDate = new Date(this.filters.dateFrom);
            filteredTransactions = filteredTransactions.filter(t => t.date >= fromDate);
        }

        if (this.filters.dateTo) {
            const toDate = new Date(this.filters.dateTo);
            toDate.setHours(23, 59, 59, 999); // End of day
            filteredTransactions = filteredTransactions.filter(t => t.date <= toDate);
        }

        // Limit to recent 100 transactions for performance
        const recentTransactions = filteredTransactions.slice(0, 100);

        tableBody.innerHTML = recentTransactions.map(transaction => `
            <tr>
                <td class="transaction-date">${this.formatDate(transaction.date)}</td>
                <td class="transaction-customer">${transaction.memberName}</td>
                <td>
                    <span class="transaction-type ${transaction.type}">
                        ${this.formatTransactionType(transaction.type)}
                    </span>
                </td>
                <td class="transaction-points ${transaction.points > 0 ? 'positive' : 'negative'}">
                    ${transaction.points > 0 ? '+' : ''}${transaction.points}
                </td>
                <td>${transaction.description}</td>
                <td class="transaction-balance">${transaction.balance}</td>
            </tr>
        `).join('');
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            tier: '',
            status: '',
            transactionType: '',
            dateFrom: '',
            dateTo: ''
        };
        
        // Reset form inputs
        const membersSearch = document.getElementById('members-search');
        const tierFilter = document.getElementById('tier-filter');
        const statusFilter = document.getElementById('status-filter');
        const transactionTypeFilter = document.getElementById('transaction-type-filter');
        const dateFromFilter = document.getElementById('date-from');
        const dateToFilter = document.getElementById('date-to');
        
        if (membersSearch) membersSearch.value = '';
        if (tierFilter) tierFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        if (transactionTypeFilter) transactionTypeFilter.value = '';
        if (dateFromFilter) dateFromFilter.value = '';
        if (dateToFilter) dateToFilter.value = '';
        
        this.filterAndRenderMembers();
        this.renderTransactions();
    }

    /**
     * Open reward modal
     */
    openRewardModal(reward = null) {
        this.currentReward = reward;
        this.isEditing = !!reward;
        
        const modal = document.getElementById('reward-modal');
        const title = document.getElementById('reward-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Reward' : 'Add Reward';
            
            if (this.isEditing) {
                this.populateRewardForm(reward);
            } else {
                this.resetRewardForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close reward modal
     */
    closeRewardModal() {
        const modal = document.getElementById('reward-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetRewardForm();
            this.currentReward = null;
            this.isEditing = false;
        }
    }

    /**
     * Open member details modal
     */
    openMemberDetails(memberId) {
        const member = this.members.find(m => m.id === memberId);
        if (!member) return;
        
        this.currentMember = member;
        
        const modal = document.getElementById('member-details-modal');
        const content = document.getElementById('member-details-content');
        
        if (modal && content) {
            content.innerHTML = this.generateMemberDetailsHtml(member);
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close member details modal
     */
    closeMemberDetails() {
        const modal = document.getElementById('member-details-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentMember = null;
        }
    }

    /**
     * Open points adjustment modal
     */
    openPointsModal(memberId) {
        const member = this.members.find(m => m.id === memberId);
        if (!member) return;
        
        this.currentMember = member;
        
        const modal = document.getElementById('points-modal');
        const currentPointsDisplay = document.getElementById('current-points-display');
        
        if (modal && currentPointsDisplay) {
            currentPointsDisplay.textContent = member.points;
            this.resetPointsForm();
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close points modal
     */
    closePointsModal() {
        const modal = document.getElementById('points-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetPointsForm();
        }
    }

    /**
     * Generate member details HTML
     */
    generateMemberDetailsHtml(member) {
        const memberTransactions = this.transactions
            .filter(t => t.memberId === member.id)
            .slice(0, 10); // Recent 10 transactions

        return `
            <div class="member-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="member-info-section">
                    <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Member Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <strong>Name:</strong> ${member.firstName} ${member.lastName}
                        </div>
                        <div>
                            <strong>Email:</strong> ${member.email}
                        </div>
                        <div>
                            <strong>Phone:</strong> ${member.phone}
                        </div>
                        <div>
                            <strong>Tier:</strong> <span class="tier-badge ${member.tier}">${member.tierName}</span>
                        </div>
                        <div>
                            <strong>Status:</strong> <span class="status-indicator ${member.status}"></span> ${this.formatStatus(member.status)}
                        </div>
                        <div>
                            <strong>Joined:</strong> ${this.formatDate(member.joinedDate)}
                        </div>
                    </div>
                </div>
                
                <div class="member-stats-section">
                    <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Loyalty Statistics</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <strong>Current Points:</strong> <span style="color: var(--color-loyalty-primary); font-weight: 600;">${member.points}</span>
                        </div>
                        <div>
                            <strong>Total Earned:</strong> ${member.pointsEarned}
                        </div>
                        <div>
                            <strong>Total Redeemed:</strong> ${member.pointsRedeemed}
                        </div>
                        <div>
                            <strong>Total Visits:</strong> ${member.visits}
                        </div>
                        <div>
                            <strong>Total Spent:</strong> £${member.totalSpent.toFixed(2)}
                        </div>
                        <div>
                            <strong>Average per Visit:</strong> £${(member.totalSpent / member.visits).toFixed(2)}
                        </div>
                        <div>
                            <strong>Last Visit:</strong> ${this.formatDate(member.lastVisit)}
                        </div>
                        <div>
                            <strong>Rewards Redeemed:</strong> ${member.rewardsRedeemed}
                        </div>
                    </div>
                </div>
            </div>
            
            ${memberTransactions.length > 0 ? `
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Recent Transactions</h3>
                <div style="max-height: 200px; overflow-y: auto;">
                    ${memberTransactions.map(transaction => `
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border);">
                            <div>
                                <div style="font-size: 0.875rem; font-weight: 500;">${transaction.description}</div>
                                <div style="font-size: 0.75rem; color: var(--color-text-secondary);">${this.formatDate(transaction.date)}</div>
                            </div>
                            <div style="text-align: right;">
                                <div class="transaction-points ${transaction.points > 0 ? 'positive' : 'negative'}" style="font-weight: 600;">
                                    ${transaction.points > 0 ? '+' : ''}${transaction.points}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--color-text-secondary);">
                                    Balance: ${transaction.balance}
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
            ` : ''}
        `;
    }

    /**
     * Populate reward form
     */
    populateRewardForm(reward) {
        document.getElementById('reward-name').value = reward.name;
        document.getElementById('reward-type').value = reward.type;
        document.getElementById('points-required').value = reward.pointsRequired;
        document.getElementById('reward-value').value = reward.value;
        document.getElementById('reward-description').value = reward.description;
        document.getElementById('expiry-days').value = reward.expiryDays;
        document.getElementById('reward-status').value = reward.status;
    }

    /**
     * Reset reward form
     */
    resetRewardForm() {
        const form = document.getElementById('reward-form');
        if (form) {
            form.reset();
        }
    }

    /**
     * Reset points form
     */
    resetPointsForm() {
        const form = document.getElementById('points-form');
        if (form) {
            form.reset();
        }
    }

    /**
     * Save reward
     */
    saveReward() {
        const formData = new FormData(document.getElementById('reward-form'));
        const rewardData = {
            name: formData.get('name'),
            type: formData.get('type'),
            pointsRequired: parseInt(formData.get('points_required')),
            value: formData.get('value'),
            description: formData.get('description'),
            expiryDays: parseInt(formData.get('expiry_days')) || 30,
            status: formData.get('status'),
            timesRedeemed: 0
        };

        if (this.isEditing) {
            // Update existing reward
            const index = this.rewards.findIndex(r => r.id === this.currentReward.id);
            if (index !== -1) {
                this.rewards[index] = { ...this.rewards[index], ...rewardData };
                this.showNotification('Reward updated successfully', 'success');
            }
        } else {
            // Add new reward
            const newReward = {
                id: Math.max(...this.rewards.map(r => r.id)) + 1,
                ...rewardData
            };
            this.rewards.push(newReward);
            this.showNotification('Reward added successfully', 'success');
        }

        this.renderRewards();
        this.closeRewardModal();
    }

    /**
     * Save points adjustment
     */
    savePointsAdjustment() {
        const formData = new FormData(document.getElementById('points-form'));
        const adjustmentType = formData.get('type');
        const amount = parseInt(formData.get('amount'));
        const reason = formData.get('reason');

        if (!this.currentMember || !adjustmentType || !amount || !reason) {
            this.showNotification('Please fill in all required fields', 'error');
            return;
        }

        let newPoints = this.currentMember.points;
        let pointsChange = 0;

        switch (adjustmentType) {
            case 'add':
                newPoints += amount;
                pointsChange = amount;
                break;
            case 'subtract':
                newPoints = Math.max(0, newPoints - amount);
                pointsChange = -(this.currentMember.points - newPoints);
                break;
            case 'set':
                pointsChange = amount - this.currentMember.points;
                newPoints = amount;
                break;
        }

        // Update member points
        this.currentMember.points = newPoints;
        
        // Update tier if necessary
        const newTier = this.getTierByPoints(newPoints);
        this.currentMember.tier = newTier.key;
        this.currentMember.tierName = newTier.name;

        // Add transaction record
        const transaction = {
            id: Math.max(...this.transactions.map(t => t.id)) + 1,
            memberId: this.currentMember.id,
            memberName: `${this.currentMember.firstName} ${this.currentMember.lastName}`,
            type: pointsChange > 0 ? 'earned' : 'redeemed',
            points: pointsChange,
            description: `Manual adjustment: ${reason}`,
            date: new Date(),
            balance: newPoints
        };
        
        this.transactions.unshift(transaction);

        // Update tier member counts
        this.tiers.forEach(tier => {
            tier.members = this.members.filter(m => m.tier === tier.key).length;
        });

        this.renderMembers();
        this.renderTiers();
        this.renderTransactions();
        this.updateStatistics();
        this.closePointsModal();
        
        this.showNotification('Points adjusted successfully', 'success');
    }

    /**
     * Edit reward
     */
    editReward(rewardId) {
        const reward = this.rewards.find(r => r.id === rewardId);
        if (reward) {
            this.openRewardModal(reward);
        }
    }

    /**
     * Delete reward
     */
    deleteReward(rewardId) {
        if (confirm('Are you sure you want to delete this reward? This action cannot be undone.')) {
            this.rewards = this.rewards.filter(r => r.id !== rewardId);
            this.renderRewards();
            this.showNotification('Reward deleted successfully', 'success');
        }
    }

    /**
     * Open program settings
     */
    openProgramSettings() {
        this.showNotification('Program settings feature coming soon', 'info');
    }

    /**
     * Export loyalty data
     */
    exportLoyaltyData() {
        const csvContent = this.generateLoyaltyCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `loyalty-data-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Loyalty data exported successfully', 'success');
    }

    /**
     * Generate loyalty CSV
     */
    generateLoyaltyCSV() {
        const headers = [
            'Member ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Points', 'Tier',
            'Status', 'Visits', 'Total Spent', 'Last Visit', 'Joined Date', 'Points Earned', 'Points Redeemed'
        ];
        
        const rows = this.members.map(member => [
            member.id,
            member.firstName,
            member.lastName,
            member.email,
            member.phone,
            member.points,
            member.tierName,
            member.status,
            member.visits,
            member.totalSpent.toFixed(2),
            member.lastVisit.toISOString().split('T')[0],
            member.joinedDate.toISOString().split('T')[0],
            member.pointsEarned,
            member.pointsRedeemed
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`).join(',')
        ).join('\n');
    }

    /**
     * Edit tiers
     */
    editTiers() {
        this.showNotification('Tier editing feature coming soon', 'info');
    }

    /**
     * Utility methods
     */
    getMemberInitials(member) {
        return `${member.firstName.charAt(0)}${member.lastName.charAt(0)}`.toUpperCase();
    }

    formatStatus(status) {
        const statusMap = {
            active: 'Active',
            inactive: 'Inactive'
        };
        return statusMap[status] || status;
    }

    formatRewardType(type) {
        const typeMap = {
            discount: 'Discount',
            free_item: 'Free Item',
            cashback: 'Cashback',
            upgrade: 'Upgrade'
        };
        return typeMap[type] || type;
    }

    formatTransactionType(type) {
        const typeMap = {
            earned: 'Earned',
            redeemed: 'Redeemed',
            expired: 'Expired'
        };
        return typeMap[type] || type;
    }

    formatDate(date) {
        if (!date) return '';
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
    window.loyaltyProgramManager = new LoyaltyProgramManager();
});
