/**
 * Professional Modal System
 * Enterprise-level modal management with accessibility, animations, and focus management
 */

class ModalSystem {
    constructor() {
        this.activeModals = new Set();
        this.scrollbarWidth = this.getScrollbarWidth();
        this.init();
    }

    init() {
        // Set scrollbar width CSS variable
        document.documentElement.style.setProperty('--scrollbar-width', `${this.scrollbarWidth}px`);
        
        // Bind global event listeners
        this.bindGlobalEvents();
        
        // Initialize existing modals
        this.initializeModals();
    }

    bindGlobalEvents() {
        // ESC key to close modals
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.activeModals.size > 0) {
                const topModal = Array.from(this.activeModals).pop();
                this.closeModal(topModal);
            }
        });

        // Click outside to close modals
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('modal-backdrop')) {
                const modal = e.target.closest('.modal');
                if (modal && !modal.dataset.persistent) {
                    this.closeModal(modal);
                }
            }
        });

        // Handle modal triggers
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest('[data-modal-target]');
            if (trigger) {
                e.preventDefault();
                const targetId = trigger.dataset.modalTarget;
                const modal = document.getElementById(targetId);
                if (modal) {
                    this.openModal(modal);
                }
            }
        });

        // Handle modal close buttons
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest('.modal-close, [data-modal-close]');
            if (closeBtn) {
                e.preventDefault();
                const modal = closeBtn.closest('.modal');
                if (modal) {
                    this.closeModal(modal);
                }
            }
        });
    }

    initializeModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            this.setupModal(modal);
        });
    }

    setupModal(modal) {
        // Ensure proper structure
        if (!modal.querySelector('.modal-overlay') && !modal.querySelector('.modal-backdrop')) {
            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            modal.appendChild(overlay);
        }

        // Ensure modal content exists
        let content = modal.querySelector('.modal-content');
        if (!content) {
            content = document.createElement('div');
            content.className = 'modal-content';
            
            // Move existing content into modal-content
            const children = Array.from(modal.children).filter(child => 
                !child.classList.contains('modal-overlay') && 
                !child.classList.contains('modal-backdrop')
            );
            
            children.forEach(child => content.appendChild(child));
            modal.appendChild(content);
        }

        // Add accessibility attributes
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.setAttribute('aria-hidden', 'true');
        
        // Make modal content focusable
        content.setAttribute('tabindex', '-1');

        // Add close button if none exists
        if (!modal.querySelector('.modal-close')) {
            this.addCloseButton(modal);
        }
    }

    addCloseButton(modal) {
        const header = modal.querySelector('.modal-header');
        if (header && !header.querySelector('.modal-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'modal-close';
            closeBtn.setAttribute('type', 'button');
            closeBtn.setAttribute('aria-label', 'Close modal');
            closeBtn.innerHTML = `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            `;
            header.appendChild(closeBtn);
        }
    }

    openModal(modal, options = {}) {
        if (typeof modal === 'string') {
            modal = document.getElementById(modal);
        }

        if (!modal) {
            console.warn('Modal not found');
            return;
        }

        // Setup modal if not already done
        this.setupModal(modal);

        // Add options as data attributes
        if (options.size) {
            modal.classList.add(`modal-${options.size}`);
        }
        if (options.type) {
            modal.classList.add(`modal-${options.type}`);
        }
        if (options.persistent) {
            modal.dataset.persistent = 'true';
        }

        // Store currently focused element
        modal.dataset.previousFocus = document.activeElement?.id || '';

        // Prevent body scroll and handle fixed elements
        if (this.activeModals.size === 0) {
            document.body.classList.add('modal-open');

            // Ensure fixed elements are properly covered
            const fixedElements = document.querySelectorAll('.admin-header, .admin-sidebar, [class*="header"], [class*="nav"], [class*="sidebar"]');
            fixedElements.forEach(el => {
                el.style.zIndex = '0';
            });
        }

        // Add to active modals
        this.activeModals.add(modal);

        // Show modal with animation
        modal.style.display = 'flex';
        modal.setAttribute('aria-hidden', 'false');
        
        // Trigger reflow for animation
        modal.offsetHeight;
        
        modal.classList.add('active');

        // Focus management
        setTimeout(() => {
            this.focusModal(modal);
        }, 100);

        // Dispatch custom event
        modal.dispatchEvent(new CustomEvent('modal:opened', {
            detail: { modal, options }
        }));

        return modal;
    }

    closeModal(modal) {
        if (typeof modal === 'string') {
            modal = document.getElementById(modal);
        }

        if (!modal || !this.activeModals.has(modal)) {
            return;
        }

        // Remove from active modals
        this.activeModals.delete(modal);

        // Remove modal classes
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');

        // Restore focus
        const previousFocusId = modal.dataset.previousFocus;
        if (previousFocusId) {
            const previousElement = document.getElementById(previousFocusId);
            if (previousElement) {
                previousElement.focus();
            }
        }

        // Hide modal after animation
        setTimeout(() => {
            modal.style.display = 'none';
            
            // Clean up classes
            modal.classList.remove('modal-sm', 'modal-lg', 'modal-xl', 'modal-fullscreen');
            modal.classList.remove('modal-danger', 'modal-success', 'modal-warning');
            delete modal.dataset.persistent;
        }, 300);

        // Restore body scroll and fixed elements if no more modals
        if (this.activeModals.size === 0) {
            document.body.classList.remove('modal-open');

            // Restore fixed elements z-index
            const fixedElements = document.querySelectorAll('.admin-header, .admin-sidebar, [class*="header"], [class*="nav"], [class*="sidebar"]');
            fixedElements.forEach(el => {
                el.style.zIndex = '';
            });
        }

        // Dispatch custom event
        modal.dispatchEvent(new CustomEvent('modal:closed', {
            detail: { modal }
        }));
    }

    closeAllModals() {
        const modals = Array.from(this.activeModals);
        modals.forEach(modal => this.closeModal(modal));
    }

    focusModal(modal) {
        const content = modal.querySelector('.modal-content');
        if (content) {
            content.focus();
        }

        // Set up focus trap
        this.setupFocusTrap(modal);
    }

    setupFocusTrap(modal) {
        const focusableElements = modal.querySelectorAll(
            'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
        );
        
        if (focusableElements.length === 0) return;

        const firstElement = focusableElements[0];
        const lastElement = focusableElements[focusableElements.length - 1];

        const handleTabKey = (e) => {
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

        modal.addEventListener('keydown', handleTabKey);

        // Store handler for cleanup
        modal._focusTrapHandler = handleTabKey;
    }

    setLoading(modal, loading = true) {
        if (typeof modal === 'string') {
            modal = document.getElementById(modal);
        }

        if (!modal) return;

        if (loading) {
            modal.classList.add('modal-loading');
        } else {
            modal.classList.remove('modal-loading');
        }
    }

    updateContent(modal, content) {
        if (typeof modal === 'string') {
            modal = document.getElementById(modal);
        }

        if (!modal) return;

        const modalBody = modal.querySelector('.modal-body, .modal-form');
        if (modalBody) {
            modalBody.innerHTML = content;
        }
    }

    updateTitle(modal, title) {
        if (typeof modal === 'string') {
            modal = document.getElementById(modal);
        }

        if (!modal) return;

        const modalTitle = modal.querySelector('.modal-title');
        if (modalTitle) {
            modalTitle.textContent = title;
        }
    }

    getScrollbarWidth() {
        const outer = document.createElement('div');
        outer.style.visibility = 'hidden';
        outer.style.overflow = 'scroll';
        outer.style.msOverflowStyle = 'scrollbar';
        document.body.appendChild(outer);

        const inner = document.createElement('div');
        outer.appendChild(inner);

        const scrollbarWidth = outer.offsetWidth - inner.offsetWidth;
        outer.parentNode.removeChild(outer);

        return scrollbarWidth;
    }

    // Utility methods for common modal types
    confirm(options = {}) {
        const {
            title = 'Confirm Action',
            message = 'Are you sure?',
            confirmText = 'Confirm',
            cancelText = 'Cancel',
            type = 'warning'
        } = options;

        return new Promise((resolve) => {
            const modalId = 'confirm-modal-' + Date.now();
            const modalHtml = `
                <div id="${modalId}" class="modal modal-${type}" role="dialog" aria-modal="true" aria-hidden="true">
                    <div class="modal-overlay"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${title}</h3>
                            <button type="button" class="modal-close" aria-label="Close modal">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-outline-secondary" data-modal-close>${cancelText}</button>
                            <button type="button" class="btn btn-${type === 'danger' ? 'danger' : 'primary'}" data-confirm>${confirmText}</button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById(modalId);

            // Handle confirm
            modal.querySelector('[data-confirm]').addEventListener('click', () => {
                this.closeModal(modal);
                setTimeout(() => modal.remove(), 300);
                resolve(true);
            });

            // Handle cancel/close
            const handleCancel = () => {
                this.closeModal(modal);
                setTimeout(() => modal.remove(), 300);
                resolve(false);
            };

            modal.querySelector('[data-modal-close]').addEventListener('click', handleCancel);
            modal.querySelector('.modal-close').addEventListener('click', handleCancel);

            this.openModal(modal, { persistent: true });
        });
    }

    alert(options = {}) {
        const {
            title = 'Alert',
            message = '',
            buttonText = 'OK',
            type = 'info'
        } = options;

        return new Promise((resolve) => {
            const modalId = 'alert-modal-' + Date.now();
            const modalHtml = `
                <div id="${modalId}" class="modal modal-${type}" role="dialog" aria-modal="true" aria-hidden="true">
                    <div class="modal-overlay"></div>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">${title}</h3>
                            <button type="button" class="modal-close" aria-label="Close modal">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-primary" data-modal-close>${buttonText}</button>
                        </div>
                    </div>
                </div>
            `;

            document.body.insertAdjacentHTML('beforeend', modalHtml);
            const modal = document.getElementById(modalId);

            // Handle close
            const handleClose = () => {
                this.closeModal(modal);
                setTimeout(() => modal.remove(), 300);
                resolve();
            };

            modal.querySelector('[data-modal-close]').addEventListener('click', handleClose);
            modal.querySelector('.modal-close').addEventListener('click', handleClose);

            this.openModal(modal, { persistent: true });
        });
    }
}

// Initialize modal system when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.modalSystem = new ModalSystem();
    });
} else {
    window.modalSystem = new ModalSystem();
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModalSystem;
}
