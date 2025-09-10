/* ==========================================================================
   GEEZ RESTAURANT ADMIN - LAYOUT JS
   Sidebar accordion, mobile toggle, and interactive features
   ========================================================================== */

/**
 * Admin Layout Manager
 * Handles sidebar accordion, mobile menu, and persistent state
 */
class AdminLayout {
  constructor() {
    this.sidebar = document.querySelector('.admin-sidebar');
    this.sidebarBackdrop = document.querySelector('.sidebar-backdrop');
    this.sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    this.sidebarClose = document.querySelector('[data-sidebar-close]');
    this.sidebarGroups = document.querySelectorAll('.sidebar-group');
    this.mobileBreakpoint = 1024;
    
    // State
    this.isMobile = window.innerWidth < this.mobileBreakpoint;
    this.sidebarOpen = false;
    this.activeGroup = null;
    
    // Initialize
        this.init();
    }

    init() {
    // Set up event listeners
    this.setupSidebarToggle();
    this.setupResponsive();
    this.setupKeyboardNav();
    
    // Note: Accordion is handled by Alpine.js in Livewire component
    console.log('Layout initialized - accordion handled by Alpine.js');
  }
  
  /**
   * Mobile sidebar toggle functionality
   */
  setupSidebarToggle() {
    // Toggle button
    if (this.sidebarToggle) {
      this.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
    }
    
    // Close button
    if (this.sidebarClose) {
      this.sidebarClose.addEventListener('click', () => this.closeSidebar());
    }
    
    // Backdrop click
    if (this.sidebarBackdrop) {
      this.sidebarBackdrop.addEventListener('click', () => this.closeSidebar());
    }
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.sidebarOpen && this.isMobile) {
        this.closeSidebar();
      }
    });
  }
  
  toggleSidebar() {
    this.sidebarOpen ? this.closeSidebar() : this.openSidebar();
  }
  
  openSidebar() {
    if (!this.isMobile) return;
    
    this.sidebarOpen = true;
    this.sidebar.classList.add('open');
    this.sidebarBackdrop?.classList.add('show');
    
    // Focus trap
    this.trapFocus();
    
    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    
    // Announce to screen readers
    this.announce('Navigation menu opened');
  }
  
  closeSidebar() {
    if (!this.isMobile) return;
    
    this.sidebarOpen = false;
    this.sidebar.classList.remove('open');
    this.sidebarBackdrop?.classList.remove('show');
    
    // Release focus trap
    this.releaseFocus();
    
    // Restore body scroll
    document.body.style.overflow = '';
    
    // Return focus to toggle button
    this.sidebarToggle?.focus();
    
    // Announce to screen readers
    this.announce('Navigation menu closed');
  }
  
  /**
   * Accordion functionality for sidebar groups (disabled - handled by Alpine.js)
   */
  setupAccordion() {
    // Accordion is now handled by Alpine.js in the Livewire component
    // This prevents conflicts between JavaScript and Alpine.js
    console.log('Accordion setup skipped - handled by Alpine.js');
  }
  
  /**
   * Set active states based on current URL (disabled - handled by Blade templates)
   */
  setActiveStates() {
    // Active states are now handled by Blade templates and Alpine.js
    console.log('Active states handled by Blade templates');
  }

  /**
   * Responsive handling
   */
  setupResponsive() {
    // Handle resize
    let resizeTimer;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => {
        const wasMobile = this.isMobile;
        this.isMobile = window.innerWidth < this.mobileBreakpoint;
        
        // Handle transition between mobile and desktop
        if (wasMobile && !this.isMobile) {
          this.closeSidebar();
        }
      }, 250);
    });
  }
  
  /**
   * Keyboard navigation
   */
  setupKeyboardNav() {
    // Add keyboard navigation for sidebar groups
    const triggers = document.querySelectorAll('.sidebar-group-trigger');
    
    triggers.forEach((trigger, index) => {
      trigger.addEventListener('keydown', (e) => {
        let targetIndex = -1;
        
        switch(e.key) {
          case 'ArrowUp':
            e.preventDefault();
            targetIndex = index - 1;
            break;
          case 'ArrowDown':
            e.preventDefault();
            targetIndex = index + 1;
            break;
          case 'Home':
            e.preventDefault();
            targetIndex = 0;
            break;
          case 'End':
            e.preventDefault();
            targetIndex = triggers.length - 1;
            break;
        }
        
        if (targetIndex >= 0 && targetIndex < triggers.length) {
          triggers[targetIndex].focus();
        }
      });
    });
  }
  
  /**
   * Focus management
   */
  trapFocus() {
    if (!this.sidebar) return;
    
    // Get all focusable elements
    const focusableElements = this.sidebar.querySelectorAll(
      'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
    );
    
    if (focusableElements.length === 0) return;
    
    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];
    
    // Focus first element
    firstElement.focus();
    
    // Trap focus
    this.focusTrapHandler = (e) => {
      if (e.key !== 'Tab') return;
      
      if (e.shiftKey) {
        if (document.activeElement === firstElement) {
          e.preventDefault();
          lastElement.focus();
        }
      } else {
        if (document.activeElement === lastElement) {
          e.preventDefault();
          firstElement.focus();
        }
      }
    };
    
    document.addEventListener('keydown', this.focusTrapHandler);
  }
  
  releaseFocus() {
    if (this.focusTrapHandler) {
      document.removeEventListener('keydown', this.focusTrapHandler);
      this.focusTrapHandler = null;
    }
  }
  
  /**
   * State persistence
   */
  saveState() {
    const state = {
      activeGroup: this.activeGroup,
      timestamp: Date.now()
    };
    
    try {
      localStorage.setItem('adminLayoutState', JSON.stringify(state));
    } catch (e) {
      console.warn('Failed to save layout state:', e);
    }
  }
  
  restoreState() {
    try {
      const saved = localStorage.getItem('adminLayoutState');
      if (!saved) return;
      
      const state = JSON.parse(saved);
      
      // Only restore if saved within last 24 hours
      if (Date.now() - state.timestamp > 24 * 60 * 60 * 1000) {
        localStorage.removeItem('adminLayoutState');
        return;
      }
      
      // Find and open saved group
      if (state.activeGroup) {
        const group = document.querySelector(`[data-group="${state.activeGroup}"]`);
        if (group) {
          this.openGroup(group);
        }
      }
    } catch (e) {
      console.warn('Failed to restore layout state:', e);
    }
  }
  
  /**
   * Accessibility helpers
   */
  announce(message) {
    // Create or update live region
    let liveRegion = document.getElementById('admin-live-region');
    if (!liveRegion) {
      liveRegion = document.createElement('div');
      liveRegion.id = 'admin-live-region';
      liveRegion.className = 'sr-only';
      liveRegion.setAttribute('aria-live', 'polite');
      liveRegion.setAttribute('aria-atomic', 'true');
      document.body.appendChild(liveRegion);
    }
    
    liveRegion.textContent = message;
    
    // Clear after announcement
    setTimeout(() => {
      liveRegion.textContent = '';
    }, 1000);
  }
}

/**
 * Theme Toggle Functionality
 */
function toggleTheme() {
  const html = document.documentElement;
  const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
  const newTheme = currentTheme === 'light' ? 'dark' : 'light';
  
  html.classList.toggle('dark');
  localStorage.setItem('theme', newTheme);
  
  // Update cookie for server-side
  document.cookie = `theme=${newTheme};path=/;max-age=${60*60*24*365}`;
  
  // Announce to screen readers
  window.adminLayout?.announce(`Switched to ${newTheme} mode`);
}

/**
 * Additional UI enhancements
 */
class UIEnhancements {
  constructor() {
    this.init();
  }
  
  init() {
    this.setupHeaderScroll();
    this.setupTooltips();
    this.setupLoadingStates();
  }
  
  /**
   * Header shadow on scroll
   */
  setupHeaderScroll() {
    const header = document.querySelector('.admin-header');
    if (!header) return;
    
    let scrolled = false;
    
    const checkScroll = () => {
      const shouldBeScrolled = window.scrollY > 10;
      
      if (shouldBeScrolled !== scrolled) {
        scrolled = shouldBeScrolled;
        header.classList.toggle('scrolled', scrolled);
      }
    };
    
    // Throttled scroll handler
    let scrollTimer;
    window.addEventListener('scroll', () => {
      if (!scrollTimer) {
        scrollTimer = setTimeout(() => {
          checkScroll();
          scrollTimer = null;
        }, 100);
      }
    }, { passive: true });
    
    // Initial check
    checkScroll();
  }
  
  /**
   * Initialize tooltips
   */
  setupTooltips() {
    // Simple tooltip implementation
    const tooltipTriggers = document.querySelectorAll('[data-tooltip]');
    
    tooltipTriggers.forEach(trigger => {
      trigger.addEventListener('mouseenter', (e) => {
        const text = trigger.dataset.tooltip;
        if (!text) return;
        
        // Create tooltip
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = text;
        tooltip.style.position = 'absolute';
        tooltip.style.zIndex = 'var(--z-tooltip)';
        
        document.body.appendChild(tooltip);
        
        // Position tooltip
        const rect = trigger.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.bottom + 8 + 'px';
        
        // Store reference
        trigger._tooltip = tooltip;
      });
      
      trigger.addEventListener('mouseleave', () => {
        if (trigger._tooltip) {
          trigger._tooltip.remove();
          trigger._tooltip = null;
        }
      });
    });
  }
  
  /**
   * Loading state helpers
   */
  setupLoadingStates() {
    // Add loading class to cards on data fetch
    window.setCardLoading = (selector, loading = true) => {
      const cards = document.querySelectorAll(selector);
      cards.forEach(card => {
        card.classList.toggle('loading', loading);
      });
    };
    
    // Helper for showing empty states
    window.showEmptyState = (container, message) => {
      const emptyHtml = `
        <div class="card-empty">
          <svg class="card-empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
            </path>
          </svg>
          <div class="card-empty-title">No Data Available</div>
          <div class="card-empty-text">${message || 'There is no data to display at this time.'}</div>
        </div>
      `;
      
      if (typeof container === 'string') {
        container = document.querySelector(container);
      }
      
      if (container) {
        container.innerHTML = emptyHtml;
      }
    };
  }
}

/**
 * Initialize on DOM ready
 */
document.addEventListener('DOMContentLoaded', () => {
  console.log('Layout JS loaded successfully');
  
  // Initialize layout manager
  window.adminLayout = new AdminLayout();
  
  // Initialize UI enhancements
  window.uiEnhancements = new UIEnhancements();
  
  // Expose utility functions
  window.adminUtils = {
    announce: (msg) => window.adminLayout.announce(msg),
    toggleSidebar: () => window.adminLayout.toggleSidebar(),
    setCardLoading: window.setCardLoading,
    showEmptyState: window.showEmptyState
  };
  
  // Make toggleTheme globally available
  window.toggleTheme = toggleTheme;
  
  console.log('All layout functions initialized');
});

// Also make toggleTheme available immediately for Alpine.js
window.toggleTheme = function() {
  const html = document.documentElement;
  const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
  const newTheme = currentTheme === 'light' ? 'dark' : 'light';
  
  html.classList.toggle('dark');
  localStorage.setItem('theme', newTheme);
  
  // Update cookie for server-side
  document.cookie = `theme=${newTheme};path=/;max-age=${60*60*24*365}`;
  
  console.log('Theme toggled to:', newTheme);
};