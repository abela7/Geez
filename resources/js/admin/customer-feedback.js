/**
 * Customer Feedback JavaScript
 * Restaurant-OS Admin Panel
 * 
 * Handles customer reviews, ratings, analytics, and reporting
 */

// Import Chart.js if available, otherwise use a fallback
let Chart;
try {
    Chart = window.Chart || require('chart.js');
} catch (e) {
    // Fallback for Chart.js - will create simple visual representations
    Chart = null;
}

class FeedbackManager {
    constructor() {
        this.reviews = [];
        this.filteredReviews = [];
        this.charts = {};
        this.currentPeriod = 'month';
        this.searchTerm = '';
        this.filters = {
            rating: '',
            category: '',
            date: '',
            status: ''
        };
        this.currentReview = null;
        this.isEditing = false;
        
        this.init();
    }

    /**
     * Initialize the feedback manager
     */
    init() {
        this.bindEvents();
        this.generateDummyData();
        this.updateStatistics();
        this.renderCharts();
        this.renderReviews();
        this.renderReports();
        this.renderInsights();
    }

    /**
     * Bind event listeners
     */
    bindEvents() {
        // Search and filter events
        this.bindSearchEvents();
        
        // Modal events
        this.bindModalEvents();
        
        // Action button events
        this.bindActionEvents();
        
        // Form events
        this.bindFormEvents();
        
        // Chart refresh events
        this.bindChartEvents();
        
        // Period selector events
        this.bindPeriodEvents();
    }

    /**
     * Bind search and filter events
     */
    bindSearchEvents() {
        const reviewsSearch = document.getElementById('reviews-search');
        const ratingFilter = document.getElementById('rating-filter');
        const categoryFilter = document.getElementById('category-filter');
        const dateFilter = document.getElementById('date-filter');
        const statusFilter = document.getElementById('status-filter');
        const clearFiltersBtn = document.querySelector('.clear-filters-btn');

        if (reviewsSearch) {
            reviewsSearch.addEventListener('input', (e) => {
                this.searchTerm = e.target.value.toLowerCase();
                this.filterAndRenderReviews();
            });
        }

        if (ratingFilter) {
            ratingFilter.addEventListener('change', (e) => {
                this.filters.rating = e.target.value;
                this.filterAndRenderReviews();
            });
        }

        if (categoryFilter) {
            categoryFilter.addEventListener('change', (e) => {
                this.filters.category = e.target.value;
                this.filterAndRenderReviews();
            });
        }

        if (dateFilter) {
            dateFilter.addEventListener('change', (e) => {
                this.filters.date = e.target.value;
                this.filterAndRenderReviews();
            });
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.filterAndRenderReviews();
            });
        }

        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', () => this.clearFilters());
        }
    }

    /**
     * Bind modal events
     */
    bindModalEvents() {
        // Review modal
        this.bindModalCloseEvents('review-modal', () => this.closeReviewModal());
        
        // Review details modal
        this.bindModalCloseEvents('review-details-modal', () => this.closeReviewDetails());

        // Escape key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeReviewModal();
                this.closeReviewDetails();
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
        const cancelBtn = modal.querySelector('.cancel-review-btn, .close-details-btn');
        const overlay = modal.querySelector('.modal-overlay');

        if (closeBtn) closeBtn.addEventListener('click', closeCallback);
        if (cancelBtn) cancelBtn.addEventListener('click', closeCallback);
        if (overlay) overlay.addEventListener('click', closeCallback);
    }

    /**
     * Bind action button events
     */
    bindActionEvents() {
        // Add review button
        document.querySelectorAll('.add-review-btn').forEach(btn => {
            btn.addEventListener('click', () => this.openReviewModal());
        });

        // Export feedback button
        const exportBtn = document.querySelector('.export-feedback-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportFeedback());
        }

        // Generate report button
        const generateReportBtn = document.querySelector('.generate-report-btn');
        if (generateReportBtn) {
            generateReportBtn.addEventListener('click', () => this.generateReport());
        }

        // Event delegation for dynamic buttons
        document.addEventListener('click', (e) => {
            // Review card click
            if (e.target.closest('.review-card') && !e.target.closest('.review-action-btn')) {
                const reviewId = parseInt(e.target.closest('.review-card').dataset.reviewId);
                this.openReviewDetails(reviewId);
            }
            
            // Review action buttons
            if (e.target.closest('.review-action-btn')) {
                e.stopPropagation();
                const action = e.target.closest('.review-action-btn').dataset.action;
                const reviewId = parseInt(e.target.closest('.review-card').dataset.reviewId);
                
                switch (action) {
                    case 'view':
                        this.openReviewDetails(reviewId);
                        break;
                    case 'edit':
                        this.editReview(reviewId);
                        break;
                    case 'approve':
                        this.approveReview(reviewId);
                        break;
                    case 'reject':
                        this.rejectReview(reviewId);
                        break;
                }
            }
            
            // Review details actions
            if (e.target.closest('.approve-review-btn')) {
                this.approveCurrentReview();
            }
            
            if (e.target.closest('.reject-review-btn')) {
                this.rejectCurrentReview();
            }
            
            if (e.target.closest('.edit-review-btn')) {
                this.editCurrentReview();
            }
        });
    }

    /**
     * Bind form events
     */
    bindFormEvents() {
        const reviewForm = document.getElementById('review-form');
        if (reviewForm) {
            reviewForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.saveReview();
            });
        }

        // Star rating events
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('star')) {
                const starRating = e.target.closest('.star-rating');
                const ratingType = starRating.dataset.rating;
                const value = parseInt(e.target.dataset.value);
                this.setStarRating(ratingType, value);
            }
        });

        // Star rating hover events
        document.addEventListener('mouseover', (e) => {
            if (e.target.classList.contains('star')) {
                const starRating = e.target.closest('.star-rating');
                const value = parseInt(e.target.dataset.value);
                this.highlightStars(starRating, value);
            }
        });

        document.addEventListener('mouseout', (e) => {
            if (e.target.classList.contains('star')) {
                const starRating = e.target.closest('.star-rating');
                const ratingType = starRating.dataset.rating;
                const currentValue = parseInt(document.getElementById(`${ratingType}-rating`).value) || 0;
                this.highlightStars(starRating, currentValue);
            }
        });
    }

    /**
     * Bind chart events
     */
    bindChartEvents() {
        document.querySelectorAll('.chart-action-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                this.refreshCharts();
            });
        });
    }

    /**
     * Bind period selector events
     */
    bindPeriodEvents() {
        const analyticsPeriod = document.getElementById('analytics-period');
        const insightsPeriod = document.getElementById('insights-period');

        if (analyticsPeriod) {
            analyticsPeriod.addEventListener('change', (e) => {
                this.currentPeriod = e.target.value;
                this.updateAnalytics();
            });
        }

        if (insightsPeriod) {
            insightsPeriod.addEventListener('change', (e) => {
                this.renderInsights();
            });
        }
    }

    /**
     * Generate dummy data
     */
    generateDummyData() {
        this.generateReviews();
    }

    /**
     * Generate customer reviews
     */
    generateReviews() {
        const firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Maria', 'William', 'Jennifer', 'Richard', 'Patricia', 'Charles', 'Linda', 'Thomas', 'Elizabeth', 'Christopher', 'Barbara'];
        const lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin'];
        const statuses = ['pending', 'approved', 'rejected'];
        
        const positiveComments = [
            'Absolutely amazing food and service! Will definitely come back.',
            'The atmosphere was perfect for our date night. Highly recommend!',
            'Best restaurant experience I\'ve had in years. Everything was perfect.',
            'Outstanding service and delicious food. The staff was very attentive.',
            'Beautiful ambiance and the food was cooked to perfection.',
            'Exceeded all expectations. The dessert was incredible!',
            'Wonderful evening with friends. Great food and atmosphere.',
            'The staff went above and beyond to make our celebration special.',
            'Fresh ingredients and creative dishes. Loved every bite!',
            'Perfect place for a business dinner. Professional and elegant.'
        ];
        
        const neutralComments = [
            'Good food overall, but the service could be improved.',
            'Nice atmosphere, food was decent but nothing special.',
            'Average experience. The food was okay but overpriced.',
            'The restaurant was clean and the staff was friendly.',
            'Food was good but took a long time to arrive.',
            'Decent meal, would consider coming back.',
            'The atmosphere was nice but the food was just okay.',
            'Service was good but the food could use more flavor.',
            'Clean restaurant with average food quality.',
            'Not bad, but not exceptional either.'
        ];
        
        const negativeComments = [
            'Very disappointed with the service and food quality.',
            'Overpriced for what you get. Won\'t be returning.',
            'The food was cold when it arrived and the service was slow.',
            'Poor experience overall. The atmosphere was too noisy.',
            'Food was bland and the service was unprofessional.',
            'Long wait times and mediocre food quality.',
            'The restaurant was dirty and the staff was rude.',
            'Terrible experience. Food was undercooked.',
            'Way too expensive for the quality provided.',
            'Would not recommend. Many better options available.'
        ];
        
        this.reviews = [];
        
        for (let i = 1; i <= 100; i++) {
            const firstName = firstNames[Math.floor(Math.random() * firstNames.length)];
            const lastName = lastNames[Math.floor(Math.random() * lastNames.length)];
            
            // Generate date within last 90 days
            const date = new Date();
            date.setDate(date.getDate() - Math.floor(Math.random() * 90));
            
            // Generate ratings (weighted towards higher ratings)
            const foodRating = this.generateWeightedRating();
            const serviceRating = this.generateWeightedRating();
            const atmosphereRating = this.generateWeightedRating();
            const overallRating = Math.round((foodRating + serviceRating + atmosphereRating) / 3);
            
            // Select comment based on overall rating
            let comment;
            if (overallRating >= 4) {
                comment = positiveComments[Math.floor(Math.random() * positiveComments.length)];
            } else if (overallRating >= 3) {
                comment = neutralComments[Math.floor(Math.random() * neutralComments.length)];
            } else {
                comment = negativeComments[Math.floor(Math.random() * negativeComments.length)];
            }
            
            // Status distribution: 80% approved, 15% pending, 5% rejected
            let status;
            const statusRand = Math.random();
            if (statusRand < 0.8) {
                status = 'approved';
            } else if (statusRand < 0.95) {
                status = 'pending';
            } else {
                status = 'rejected';
            }
            
            this.reviews.push({
                id: i,
                customerName: `${firstName} ${lastName}`,
                customerEmail: `${firstName.toLowerCase()}.${lastName.toLowerCase()}@example.com`,
                date: date,
                foodRating: foodRating,
                serviceRating: serviceRating,
                atmosphereRating: atmosphereRating,
                overallRating: overallRating,
                comment: comment,
                status: status,
                createdAt: date,
                updatedAt: date
            });
        }
        
        // Sort by date (most recent first)
        this.reviews.sort((a, b) => b.date - a.date);
    }

    /**
     * Generate weighted rating (more likely to be higher)
     */
    generateWeightedRating() {
        const weights = [0.05, 0.1, 0.15, 0.3, 0.4]; // 1-5 stars probability
        const rand = Math.random();
        let cumulative = 0;
        
        for (let i = 0; i < weights.length; i++) {
            cumulative += weights[i];
            if (rand <= cumulative) {
                return i + 1;
            }
        }
        return 5; // fallback
    }

    /**
     * Update statistics
     */
    updateStatistics() {
        const approvedReviews = this.reviews.filter(r => r.status === 'approved');
        
        // Overall rating
        const overallRating = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.overallRating, 0) / approvedReviews.length).toFixed(1)
            : '0.0';
        
        // Category ratings
        const foodRating = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.foodRating, 0) / approvedReviews.length).toFixed(1)
            : '0.0';
        
        const serviceRating = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.serviceRating, 0) / approvedReviews.length).toFixed(1)
            : '0.0';
        
        const atmosphereRating = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.atmosphereRating, 0) / approvedReviews.length).toFixed(1)
            : '0.0';
        
        // Total reviews
        const totalReviews = this.reviews.length;
        
        // Calculate change (dummy percentages for demo)
        const reviewsChange = Math.floor(Math.random() * 20) + 5; // +5% to +25%
        
        // Update DOM
        document.getElementById('overall-rating').textContent = overallRating;
        document.getElementById('total-reviews').textContent = totalReviews;
        document.getElementById('food-rating').textContent = foodRating;
        document.getElementById('service-rating').textContent = serviceRating;
        document.getElementById('atmosphere-rating').textContent = atmosphereRating;
        
        // Update star displays
        this.updateStarDisplay('overall-stars', parseFloat(overallRating));
        this.updateStarDisplay('food-stars', parseFloat(foodRating));
        this.updateStarDisplay('service-stars', parseFloat(serviceRating));
        this.updateStarDisplay('atmosphere-stars', parseFloat(atmosphereRating));
        
        // Update change indicator
        const changeElement = document.getElementById('reviews-change');
        if (changeElement) {
            changeElement.textContent = `+${reviewsChange}%`;
            changeElement.className = 'stat-change';
        }
    }

    /**
     * Update star display
     */
    updateStarDisplay(elementId, rating) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= fullStars) {
                html += '<span class="star">★</span>';
            } else if (i === fullStars + 1 && hasHalfStar) {
                html += '<span class="star">☆</span>'; // Half star representation
            } else {
                html += '<span class="star empty">☆</span>';
            }
        }
        
        element.innerHTML = html;
    }

    /**
     * Render charts
     */
    renderCharts() {
        this.renderRatingDistributionChart();
        this.renderCategoryRatingsChart();
        this.renderReviewsTimelineChart();
        this.renderSentimentAnalysisChart();
    }

    /**
     * Render rating distribution chart
     */
    renderRatingDistributionChart() {
        const canvas = document.getElementById('rating-distribution-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Count ratings
        const ratingCounts = [0, 0, 0, 0, 0]; // 1-5 stars
        this.reviews.filter(r => r.status === 'approved').forEach(review => {
            ratingCounts[review.overallRating - 1]++;
        });
        
        const labels = ['1 Star', '2 Stars', '3 Stars', '4 Stars', '5 Stars'];
        const data = ratingCounts;

        if (Chart) {
            this.charts.ratingDistribution = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Reviews',
                        data: data,
                        backgroundColor: [
                            '#ef4444', '#f97316', '#f59e0b', '#84cc16', '#10b981'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'bar');
        }
    }

    /**
     * Render category ratings chart
     */
    renderCategoryRatingsChart() {
        const canvas = document.getElementById('category-ratings-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        const approvedReviews = this.reviews.filter(r => r.status === 'approved');
        
        const foodAvg = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.foodRating, 0) / approvedReviews.length).toFixed(1)
            : 0;
        
        const serviceAvg = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.serviceRating, 0) / approvedReviews.length).toFixed(1)
            : 0;
        
        const atmosphereAvg = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.atmosphereRating, 0) / approvedReviews.length).toFixed(1)
            : 0;
        
        const labels = ['Food', 'Service', 'Atmosphere'];
        const data = [parseFloat(foodAvg), parseFloat(serviceAvg), parseFloat(atmosphereAvg)];

        if (Chart) {
            this.charts.categoryRatings = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Average Rating',
                        data: data,
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#d97706',
                        pointHoverBackgroundColor: '#d97706',
                        pointHoverBorderColor: '#f59e0b'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 5,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'radar');
        }
    }

    /**
     * Render reviews timeline chart
     */
    renderReviewsTimelineChart() {
        const canvas = document.getElementById('reviews-timeline-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Generate data for the last 30 days
        const labels = [];
        const data = [];
        const now = new Date();
        
        for (let i = 29; i >= 0; i--) {
            const date = new Date(now);
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
            
            const dayReviews = this.reviews.filter(r => 
                r.date.toDateString() === date.toDateString()
            );
            data.push(dayReviews.length);
        }

        if (Chart) {
            this.charts.reviewsTimeline = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Reviews per Day',
                        data: data,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'line');
        }
    }

    /**
     * Render sentiment analysis chart
     */
    renderSentimentAnalysisChart() {
        const canvas = document.getElementById('sentiment-analysis-chart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Categorize reviews by rating into sentiment
        const approvedReviews = this.reviews.filter(r => r.status === 'approved');
        let positive = 0, neutral = 0, negative = 0;
        
        approvedReviews.forEach(review => {
            if (review.overallRating >= 4) {
                positive++;
            } else if (review.overallRating >= 3) {
                neutral++;
            } else {
                negative++;
            }
        });
        
        const labels = ['Positive', 'Neutral', 'Negative'];
        const data = [positive, neutral, negative];

        if (Chart) {
            this.charts.sentimentAnalysis = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#10b981', '#6b7280', '#ef4444'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        } else {
            this.renderFallbackChart(ctx, data, labels, 'doughnut');
        }
    }

    /**
     * Render fallback chart when Chart.js is not available
     */
    renderFallbackChart(ctx, data, labels, type) {
        const canvas = ctx.canvas;
        const width = canvas.width;
        const height = canvas.height;
        
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = '#6b7280';
        ctx.font = '14px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(`${type.charAt(0).toUpperCase() + type.slice(1)} Chart`, width / 2, height / 2 - 10);
        ctx.fillText('(Chart.js not loaded)', width / 2, height / 2 + 10);
    }

    /**
     * Refresh all charts
     */
    refreshCharts() {
        Object.values(this.charts).forEach(chart => {
            if (chart && chart.destroy) {
                chart.destroy();
            }
        });
        this.charts = {};
        this.renderCharts();
        this.showNotification('Charts refreshed', 'success');
    }

    /**
     * Filter and render reviews
     */
    filterAndRenderReviews() {
        this.filteredReviews = this.reviews.filter(review => {
            // Search filter
            const searchMatch = !this.searchTerm || 
                review.customerName.toLowerCase().includes(this.searchTerm) ||
                review.comment.toLowerCase().includes(this.searchTerm) ||
                review.customerEmail.toLowerCase().includes(this.searchTerm);

            // Rating filter
            const ratingMatch = !this.filters.rating || 
                review.overallRating.toString() === this.filters.rating;

            // Category filter (for demo, we'll use overall rating)
            const categoryMatch = !this.filters.category || true; // All categories for now

            // Date filter
            const dateMatch = !this.filters.date || 
                review.date.toISOString().split('T')[0] === this.filters.date;

            // Status filter
            const statusMatch = !this.filters.status || review.status === this.filters.status;

            return searchMatch && ratingMatch && categoryMatch && dateMatch && statusMatch;
        });

        this.renderReviews();
    }

    /**
     * Render reviews
     */
    renderReviews() {
        const reviewsGrid = document.getElementById('reviews-grid');
        if (!reviewsGrid) return;

        const reviewsToShow = this.filteredReviews.length ? this.filteredReviews : this.reviews;

        if (reviewsToShow.length === 0) {
            reviewsGrid.innerHTML = `
                <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                    <p>No reviews found matching your criteria.</p>
                </div>
            `;
            return;
        }

        reviewsGrid.innerHTML = reviewsToShow.map(review => `
            <div class="review-card" data-review-id="${review.id}">
                <div class="review-header">
                    <div class="review-customer">
                        <div class="customer-name">${review.customerName}</div>
                        <div class="review-date">${this.formatDate(review.date)}</div>
                    </div>
                    <div class="review-status ${review.status}">${this.formatStatus(review.status)}</div>
                </div>
                
                <div class="review-ratings">
                    <div class="rating-row">
                        <span class="rating-category">Food</span>
                        <div class="rating-value">
                            <span class="rating-number">${review.foodRating}</span>
                            <div class="rating-stars">${this.generateStars(review.foodRating)}</div>
                        </div>
                    </div>
                    <div class="rating-row">
                        <span class="rating-category">Service</span>
                        <div class="rating-value">
                            <span class="rating-number">${review.serviceRating}</span>
                            <div class="rating-stars">${this.generateStars(review.serviceRating)}</div>
                        </div>
                    </div>
                    <div class="rating-row">
                        <span class="rating-category">Atmosphere</span>
                        <div class="rating-value">
                            <span class="rating-number">${review.atmosphereRating}</span>
                            <div class="rating-stars">${this.generateStars(review.atmosphereRating)}</div>
                        </div>
                    </div>
                </div>
                
                <div class="review-comment">${review.comment}</div>
                
                <div class="review-actions">
                    <button class="review-action-btn view" data-action="view" title="View Details">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                    <button class="review-action-btn edit" data-action="edit" title="Edit Review">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </button>
                    ${review.status === 'pending' ? `
                        <button class="review-action-btn approve" data-action="approve" title="Approve Review">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </button>
                        <button class="review-action-btn reject" data-action="reject" title="Reject Review">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    ` : ''}
                </div>
            </div>
        `).join('');
    }

    /**
     * Generate stars HTML
     */
    generateStars(rating) {
        let html = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= rating) {
                html += '<span class="star">★</span>';
            } else {
                html += '<span class="star empty">☆</span>';
            }
        }
        return html;
    }

    /**
     * Render reports
     */
    renderReports() {
        const reportsContent = document.getElementById('reports-content');
        if (!reportsContent) return;

        // Generate monthly report
        const now = new Date();
        const monthStart = new Date(now.getFullYear(), now.getMonth(), 1);
        const monthReviews = this.reviews.filter(r => r.date >= monthStart);
        
        const approvedReviews = monthReviews.filter(r => r.status === 'approved');
        const avgRating = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.overallRating, 0) / approvedReviews.length).toFixed(1)
            : '0.0';

        reportsContent.innerHTML = `
            <div class="report-summary">
                <h4>Monthly Feedback Report</h4>
                <p>Customer feedback summary for ${now.toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</p>
            </div>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Metric</th>
                        <th>Value</th>
                        <th>Previous Month</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Reviews</td>
                        <td>${monthReviews.length}</td>
                        <td>${Math.floor(monthReviews.length * 0.85)}</td>
                        <td style="color: #10b981;">+${Math.floor(monthReviews.length * 0.15)}</td>
                    </tr>
                    <tr>
                        <td>Average Rating</td>
                        <td>${avgRating}</td>
                        <td>${(parseFloat(avgRating) - 0.2).toFixed(1)}</td>
                        <td style="color: #10b981;">+0.2</td>
                    </tr>
                    <tr>
                        <td>Approved Reviews</td>
                        <td>${approvedReviews.length}</td>
                        <td>${Math.floor(approvedReviews.length * 0.9)}</td>
                        <td style="color: #10b981;">+${Math.floor(approvedReviews.length * 0.1)}</td>
                    </tr>
                    <tr>
                        <td>Pending Reviews</td>
                        <td>${monthReviews.filter(r => r.status === 'pending').length}</td>
                        <td>${Math.floor(monthReviews.filter(r => r.status === 'pending').length * 1.2)}</td>
                        <td style="color: #10b981;">-${Math.floor(monthReviews.filter(r => r.status === 'pending').length * 0.2)}</td>
                    </tr>
                </tbody>
            </table>
        `;
    }

    /**
     * Render insights
     */
    renderInsights() {
        this.renderSatisfactionTrends();
        this.renderImprovementAreas();
        this.renderCommonKeywords();
        this.renderActionRecommendations();
    }

    /**
     * Render satisfaction trends
     */
    renderSatisfactionTrends() {
        const trendsContainer = document.getElementById('satisfaction-trends');
        if (!trendsContainer) return;

        const approvedReviews = this.reviews.filter(r => r.status === 'approved');
        const avgRating = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.overallRating, 0) / approvedReviews.length).toFixed(1)
            : 0;

        const positiveReviews = approvedReviews.filter(r => r.overallRating >= 4).length;
        const satisfactionRate = approvedReviews.length > 0 
            ? Math.round((positiveReviews / approvedReviews.length) * 100)
            : 0;

        trendsContainer.innerHTML = `
            <div class="insight-item">
                <span class="insight-item-label">Average Rating</span>
                <span class="insight-item-value">${avgRating}/5.0</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Satisfaction Rate</span>
                <span class="insight-item-value">${satisfactionRate}%</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Total Reviews</span>
                <span class="insight-item-value">${approvedReviews.length}</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Trend</span>
                <span class="insight-item-value" style="color: #10b981;">↗ Improving</span>
            </div>
        `;
    }

    /**
     * Render improvement areas
     */
    renderImprovementAreas() {
        const improvementContainer = document.getElementById('improvement-areas');
        if (!improvementContainer) return;

        const approvedReviews = this.reviews.filter(r => r.status === 'approved');
        
        const foodAvg = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.foodRating, 0) / approvedReviews.length).toFixed(1)
            : 0;
        
        const serviceAvg = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.serviceRating, 0) / approvedReviews.length).toFixed(1)
            : 0;
        
        const atmosphereAvg = approvedReviews.length > 0 
            ? (approvedReviews.reduce((sum, r) => sum + r.atmosphereRating, 0) / approvedReviews.length).toFixed(1)
            : 0;

        // Find lowest rated category
        const categories = [
            { name: 'Food', rating: parseFloat(foodAvg) },
            { name: 'Service', rating: parseFloat(serviceAvg) },
            { name: 'Atmosphere', rating: parseFloat(atmosphereAvg) }
        ];
        
        categories.sort((a, b) => a.rating - b.rating);

        improvementContainer.innerHTML = `
            <div class="insight-item">
                <span class="insight-item-label">Lowest Rated</span>
                <span class="insight-item-value">${categories[0].name} (${categories[0].rating})</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Needs Attention</span>
                <span class="insight-item-value">${categories[1].name} (${categories[1].rating})</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Strongest Area</span>
                <span class="insight-item-value">${categories[2].name} (${categories[2].rating})</span>
            </div>
            <div class="insight-item">
                <span class="insight-item-label">Priority</span>
                <span class="insight-item-value" style="color: #f59e0b;">Focus on ${categories[0].name}</span>
            </div>
        `;
    }

    /**
     * Render common keywords
     */
    renderCommonKeywords() {
        const keywordsContainer = document.getElementById('common-keywords');
        if (!keywordsContainer) return;

        // Extract keywords from comments (simplified)
        const keywords = [
            'delicious', 'excellent', 'amazing', 'great', 'perfect',
            'friendly', 'professional', 'attentive', 'quick', 'slow',
            'atmosphere', 'ambiance', 'cozy', 'noisy', 'clean'
        ];

        keywordsContainer.innerHTML = `
            <div class="keyword-cloud">
                ${keywords.slice(0, 10).map(keyword => 
                    `<span class="keyword-tag">${keyword}</span>`
                ).join('')}
            </div>
        `;
    }

    /**
     * Render action recommendations
     */
    renderActionRecommendations() {
        const recommendationsContainer = document.getElementById('action-recommendations');
        if (!recommendationsContainer) return;

        const recommendations = [
            {
                title: 'Improve Service Speed',
                description: 'Several reviews mention slow service. Consider additional staff training or process optimization.'
            },
            {
                title: 'Enhance Food Presentation',
                description: 'Focus on visual appeal of dishes to improve overall dining experience and photo opportunities.'
            },
            {
                title: 'Address Noise Levels',
                description: 'Some customers find the atmosphere too noisy. Consider acoustic improvements during peak hours.'
            }
        ];

        recommendationsContainer.innerHTML = recommendations.map(rec => `
            <div class="insight-recommendation">
                <div class="recommendation-title">${rec.title}</div>
                <div class="recommendation-description">${rec.description}</div>
            </div>
        `).join('');
    }

    /**
     * Clear all filters
     */
    clearFilters() {
        this.searchTerm = '';
        this.filters = {
            rating: '',
            category: '',
            date: '',
            status: ''
        };
        
        // Reset form inputs
        const reviewsSearch = document.getElementById('reviews-search');
        const ratingFilter = document.getElementById('rating-filter');
        const categoryFilter = document.getElementById('category-filter');
        const dateFilter = document.getElementById('date-filter');
        const statusFilter = document.getElementById('status-filter');
        
        if (reviewsSearch) reviewsSearch.value = '';
        if (ratingFilter) ratingFilter.value = '';
        if (categoryFilter) categoryFilter.value = '';
        if (dateFilter) dateFilter.value = '';
        if (statusFilter) statusFilter.value = '';
        
        this.filterAndRenderReviews();
    }

    /**
     * Open review modal
     */
    openReviewModal(review = null) {
        this.currentReview = review;
        this.isEditing = !!review;
        
        const modal = document.getElementById('review-modal');
        const title = document.getElementById('review-modal-title');
        
        if (modal && title) {
            title.textContent = this.isEditing ? 'Edit Review' : 'Add Review';
            
            if (this.isEditing) {
                this.populateReviewForm(review);
            } else {
                this.resetReviewForm();
            }
            
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close review modal
     */
    closeReviewModal() {
        const modal = document.getElementById('review-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.resetReviewForm();
            this.currentReview = null;
            this.isEditing = false;
        }
    }

    /**
     * Populate review form
     */
    populateReviewForm(review) {
        document.getElementById('customer-name').value = review.customerName;
        document.getElementById('customer-email').value = review.customerEmail;
        document.getElementById('review-date').value = review.date.toISOString().slice(0, 16);
        document.getElementById('review-status').value = review.status;
        document.getElementById('review-comment').value = review.comment;
        
        // Set star ratings
        this.setStarRating('food', review.foodRating);
        this.setStarRating('service', review.serviceRating);
        this.setStarRating('atmosphere', review.atmosphereRating);
    }

    /**
     * Reset review form
     */
    resetReviewForm() {
        const form = document.getElementById('review-form');
        if (form) {
            form.reset();
            // Reset star ratings
            this.setStarRating('food', 0);
            this.setStarRating('service', 0);
            this.setStarRating('atmosphere', 0);
            
            // Set default date to now
            const now = new Date().toISOString().slice(0, 16);
            document.getElementById('review-date').value = now;
        }
    }

    /**
     * Set star rating
     */
    setStarRating(type, value) {
        const input = document.getElementById(`${type}-rating`);
        const starRating = document.querySelector(`[data-rating="${type}"]`);
        
        if (input) input.value = value;
        if (starRating) this.highlightStars(starRating, value);
    }

    /**
     * Highlight stars
     */
    highlightStars(starRating, value) {
        const stars = starRating.querySelectorAll('.star');
        stars.forEach((star, index) => {
            if (index < value) {
                star.classList.add('filled');
                star.classList.remove('empty');
            } else {
                star.classList.remove('filled');
                star.classList.add('empty');
            }
        });
    }

    /**
     * Save review
     */
    saveReview() {
        const formData = new FormData(document.getElementById('review-form'));
        
        const reviewData = {
            customerName: formData.get('customer_name'),
            customerEmail: formData.get('customer_email'),
            date: new Date(formData.get('review_date')),
            foodRating: parseInt(formData.get('food_rating')) || 0,
            serviceRating: parseInt(formData.get('service_rating')) || 0,
            atmosphereRating: parseInt(formData.get('atmosphere_rating')) || 0,
            comment: formData.get('comment'),
            status: formData.get('status')
        };

        // Calculate overall rating
        reviewData.overallRating = Math.round((reviewData.foodRating + reviewData.serviceRating + reviewData.atmosphereRating) / 3);

        if (this.isEditing) {
            // Update existing review
            const index = this.reviews.findIndex(r => r.id === this.currentReview.id);
            if (index !== -1) {
                this.reviews[index] = { ...this.reviews[index], ...reviewData, updatedAt: new Date() };
                this.showNotification('Review updated successfully', 'success');
            }
        } else {
            // Add new review
            const newReview = {
                id: Math.max(...this.reviews.map(r => r.id)) + 1,
                ...reviewData,
                createdAt: new Date(),
                updatedAt: new Date()
            };
            this.reviews.unshift(newReview);
            this.showNotification('Review added successfully', 'success');
        }

        this.updateStatistics();
        this.refreshCharts();
        this.renderReviews();
        this.closeReviewModal();
    }

    /**
     * Open review details modal
     */
    openReviewDetails(reviewId) {
        const review = this.reviews.find(r => r.id === reviewId);
        if (!review) return;
        
        this.currentReview = review;
        
        const modal = document.getElementById('review-details-modal');
        const content = document.getElementById('review-details-content');
        
        if (modal && content) {
            content.innerHTML = this.generateReviewDetailsHtml(review);
            modal.style.display = 'flex';
            modal.setAttribute('aria-hidden', 'false');
        }
    }

    /**
     * Close review details modal
     */
    closeReviewDetails() {
        const modal = document.getElementById('review-details-modal');
        if (modal) {
            modal.style.display = 'none';
            modal.setAttribute('aria-hidden', 'true');
            this.currentReview = null;
        }
    }

    /**
     * Generate review details HTML
     */
    generateReviewDetailsHtml(review) {
        return `
            <div class="review-details-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div class="review-info-section">
                    <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Review Information</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <strong>Customer:</strong> ${review.customerName}
                        </div>
                        <div>
                            <strong>Email:</strong> ${review.customerEmail}
                        </div>
                        <div>
                            <strong>Date:</strong> ${this.formatDate(review.date)}
                        </div>
                        <div>
                            <strong>Status:</strong> <span class="review-status ${review.status}">${this.formatStatus(review.status)}</span>
                        </div>
                        <div>
                            <strong>Overall Rating:</strong> ${review.overallRating}/5 ${this.generateStars(review.overallRating)}
                        </div>
                    </div>
                </div>
                
                <div class="ratings-section">
                    <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Category Ratings</h3>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>Food Quality:</strong>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span>${review.foodRating}/5</span>
                                <div>${this.generateStars(review.foodRating)}</div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>Service Quality:</strong>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span>${review.serviceRating}/5</span>
                                <div>${this.generateStars(review.serviceRating)}</div>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>Atmosphere:</strong>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span>${review.atmosphereRating}/5</span>
                                <div>${this.generateStars(review.atmosphereRating)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Customer Comment</h3>
                <div style="background: var(--color-bg-tertiary); padding: 1rem; border-radius: var(--border-radius-md); line-height: 1.6;">
                    "${review.comment}"
                </div>
            </div>
            
            <div style="margin-top: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--color-text-primary);">Review History</h3>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.875rem; color: var(--color-text-secondary);">
                    <div><strong>Created:</strong> ${this.formatDateTime(review.createdAt)}</div>
                    <div><strong>Last Updated:</strong> ${this.formatDateTime(review.updatedAt)}</div>
                </div>
            </div>
        `;
    }

    /**
     * Review action methods
     */
    editReview(reviewId) {
        const review = this.reviews.find(r => r.id === reviewId);
        if (review) {
            this.openReviewModal(review);
        }
    }

    approveReview(reviewId) {
        const review = this.reviews.find(r => r.id === reviewId);
        if (review) {
            review.status = 'approved';
            review.updatedAt = new Date();
            this.updateStatistics();
            this.refreshCharts();
            this.renderReviews();
            this.showNotification('Review approved', 'success');
        }
    }

    rejectReview(reviewId) {
        if (confirm('Are you sure you want to reject this review?')) {
            const review = this.reviews.find(r => r.id === reviewId);
            if (review) {
                review.status = 'rejected';
                review.updatedAt = new Date();
                this.updateStatistics();
                this.refreshCharts();
                this.renderReviews();
                this.showNotification('Review rejected', 'success');
            }
        }
    }

    approveCurrentReview() {
        if (this.currentReview) {
            this.approveReview(this.currentReview.id);
            this.closeReviewDetails();
        }
    }

    rejectCurrentReview() {
        if (this.currentReview) {
            this.rejectReview(this.currentReview.id);
            this.closeReviewDetails();
        }
    }

    editCurrentReview() {
        if (this.currentReview) {
            this.closeReviewDetails();
            this.openReviewModal(this.currentReview);
        }
    }

    /**
     * Update analytics based on period
     */
    updateAnalytics() {
        this.refreshCharts();
        this.renderReports();
        this.renderInsights();
    }

    /**
     * Generate report
     */
    generateReport() {
        this.renderReports();
        this.showNotification('Report generated successfully', 'success');
    }

    /**
     * Export feedback
     */
    exportFeedback() {
        const csvContent = this.generateFeedbackCSV();
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `customer-feedback-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        this.showNotification('Feedback exported successfully', 'success');
    }

    /**
     * Generate feedback CSV
     */
    generateFeedbackCSV() {
        const headers = [
            'ID', 'Customer Name', 'Email', 'Date', 'Food Rating', 'Service Rating', 
            'Atmosphere Rating', 'Overall Rating', 'Comment', 'Status', 'Created At'
        ];
        
        const rows = this.reviews.map(review => [
            review.id,
            review.customerName,
            review.customerEmail,
            review.date.toISOString().split('T')[0],
            review.foodRating,
            review.serviceRating,
            review.atmosphereRating,
            review.overallRating,
            review.comment.replace(/"/g, '""'),
            review.status,
            review.createdAt.toISOString()
        ]);
        
        return [headers, ...rows].map(row => 
            row.map(field => `"${String(field)}"`).join(',')
        ).join('\n');
    }

    /**
     * Utility methods
     */
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

    formatStatus(status) {
        const statusMap = {
            pending: 'Pending',
            approved: 'Approved',
            rejected: 'Rejected'
        };
        return statusMap[status] || status;
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
    window.feedbackManager = new FeedbackManager();
});
