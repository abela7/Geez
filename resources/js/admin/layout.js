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
    
    // Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.sidebarOpen) {
        this.closeSidebar();
      }
    });
  }
  
  toggleSidebar() {
    this.sidebarOpen = !this.sidebarOpen;
    this.updateSidebarState();
  }
  
  closeSidebar() {
    this.sidebarOpen = false;
    this.updateSidebarState();
  }

  updateSidebarState() {
    if (this.sidebar) {
      this.sidebar.classList.toggle('mobile-open', this.sidebarOpen);
    }
    
    // Prevent body scroll when sidebar is open on mobile
    if (this.isMobile) {
      document.body.style.overflow = this.sidebarOpen ? 'hidden' : '';
    }
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
    triggers.forEach(trigger => {
      trigger.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
          trigger.click();
        }
      });
    });
  }
  
  /**
   * Announce changes for screen readers
   */
  announce(message) {
    const announcer = document.createElement('div');
    announcer.setAttribute('aria-live', 'polite');
    announcer.setAttribute('aria-atomic', 'true');
    announcer.className = 'sr-only';
    announcer.textContent = message;
    
    document.body.appendChild(announcer);
    setTimeout(() => document.body.removeChild(announcer), 1000);
    }
  }
  
  /**
 * UI Enhancements
 * Additional UI utilities and enhancements
 */
class UIEnhancements {
  constructor() {
    this.init();
  }

  init() {
    this.setupScrollEffects();
    this.setupLoadingStates();
    console.log('UI enhancements initialized');
  }

  setupScrollEffects() {
    const header = document.querySelector('.admin-header');
    if (!header) return;

    let lastScrollTop = 0;
    window.addEventListener('scroll', () => {
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      // Add scrolled class for shadow effect
      if (scrollTop > 10) {
        header.classList.add('scrolled');
      } else {
        header.classList.remove('scrolled');
      }
      
      lastScrollTop = scrollTop;
    });
  }

  setupLoadingStates() {
    // Add loading state utilities
    window.setCardLoading = (cardElement, loading = true) => {
      if (loading) {
        cardElement.classList.add('loading');
      } else {
        cardElement.classList.remove('loading');
      }
    };

    window.showEmptyState = (container, message = 'No data available') => {
      container.innerHTML = `
        <div class="empty-state text-center py-8">
          <svg class="w-16 h-16 mx-auto text-muted mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          <p class="text-muted">${message}</p>
        </div>
      `;
    };
  }
}

/**
 * Theme Toggle Function
 * Global theme switching functionality
 */
function toggleTheme() {
  console.log('toggleTheme called');
  const html = document.documentElement;
  const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
  const newTheme = currentTheme === 'light' ? 'dark' : 'light';
  
  html.classList.toggle('dark');
  localStorage.setItem('theme', newTheme);
  
  // Update cookie for server-side
  document.cookie = `theme=${newTheme};path=/;max-age=${60*60*24*365}`;
  
  console.log('Theme toggled to:', newTheme);
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