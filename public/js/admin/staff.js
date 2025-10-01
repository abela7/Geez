/* ==========================================================================
   STAFF MANAGEMENT - SECTION-SPECIFIC JAVASCRIPT
   Interactive features for staff management pages
   ========================================================================== */

/**
 * Staff Management Controller
 * Handles staff-specific interactions and features
 */
class StaffManager {
  constructor() {
    this.init();
  }
  
  init() {
    // Initialize staff-specific features
    this.setupQuickActions();
    this.setupActivityRefresh();
    this.setupStatsAnimation();
  }
  
  /**
   * Setup quick action buttons
   */
  setupQuickActions() {
    const quickActionButtons = document.querySelectorAll('.btn-primary, .btn-secondary');
    
    quickActionButtons.forEach(button => {
      button.addEventListener('click', (e) => {
        // Add loading state
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        
        // Simulate action (replace with actual functionality)
        setTimeout(() => {
          button.disabled = false;
          button.innerHTML = originalText;
        }, 1500);
      });
    });
  }
  
  /**
   * Auto-refresh recent activity
   */
  setupActivityRefresh() {
    // Refresh activity every 30 seconds
    setInterval(() => {
      this.refreshActivity();
    }, 30000);
  }
  
  refreshActivity() {
    // This would typically fetch from an API
    console.log('Refreshing staff activity...');
  }
  
  /**
   * Animate statistics on page load
   */
  setupStatsAnimation() {
    const statValues = document.querySelectorAll('.stat-card-value');
    
    statValues.forEach(stat => {
      const finalValue = parseInt(stat.textContent);
      stat.textContent = '0';
      
      // Animate to final value
      this.animateValue(stat, 0, finalValue, 1000);
    });
  }
  
  animateValue(element, start, end, duration) {
    const startTime = performance.now();
    
    const updateValue = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      
      // Easing function
      const easeOut = 1 - Math.pow(1 - progress, 3);
      const currentValue = Math.round(start + (end - start) * easeOut);
      
      element.textContent = currentValue;
      
      if (progress < 1) {
        requestAnimationFrame(updateValue);
      }
    };
    
    requestAnimationFrame(updateValue);
  }
}

/**
 * Initialize on DOM ready
 */
document.addEventListener('DOMContentLoaded', () => {
  console.log('Staff JS loaded successfully');
  window.staffManager = new StaffManager();
});

// Remove any Alpine.js initialization from here to prevent conflicts