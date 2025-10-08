/**
 * Modal Portal System
 * Moves modals outside the main content area to prevent z-index stacking issues
 */

class ModalPortal {
    constructor() {
        this.portal = null;
        this.init();
    }

    init() {
        // Create or get the modal portal
        this.portal = document.getElementById('modal-portal');
        if (!this.portal) {
            this.portal = document.createElement('div');
            this.portal.id = 'modal-portal';
            this.portal.style.cssText = 'position: fixed; top: 0; left: 0; pointer-events: none; z-index: 9999;';
            document.body.appendChild(this.portal);
        }

        // Move existing modals to portal on DOM ready
        this.moveModalsToPortal();
        
        // Watch for dynamically added modals
        this.observeModalChanges();
    }

    moveModalsToPortal() {
        // Find all modal overlays in the document
        const modals = document.querySelectorAll('.modal-overlay');
        
        modals.forEach(modal => {
            if (!this.portal.contains(modal)) {
                // Enable pointer events when modal is active
                const originalDisplay = modal.style.display;
                
                // Move modal to portal
                this.portal.appendChild(modal);
                
                // Update modal overlay styles for portal
                modal.style.pointerEvents = 'auto';
                
                // Watch for visibility changes
                this.watchModalVisibility(modal);
            }
        });
    }

    watchModalVisibility(modal) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    const isHidden = modal.classList.contains('hidden') || modal.style.display === 'none';
                    
                    // Update portal pointer events based on any visible modals
                    this.updatePortalPointerEvents();
                }
            });
        });

        observer.observe(modal, {
            attributes: true,
            attributeFilter: ['class', 'style']
        });

        // Also watch for style changes
        const styleObserver = new MutationObserver(() => {
            this.updatePortalPointerEvents();
        });

        styleObserver.observe(modal, {
            attributes: true,
            attributeFilter: ['style']
        });
    }

    updatePortalPointerEvents() {
        const visibleModals = this.portal.querySelectorAll('.modal-overlay:not(.hidden)');
        const hasVisibleModal = Array.from(visibleModals).some(modal => 
            modal.style.display !== 'none' && !modal.classList.contains('hidden')
        );
        
        // Enable portal pointer events if any modal is visible
        this.portal.style.pointerEvents = hasVisibleModal ? 'auto' : 'none';
    }

    observeModalChanges() {
        // Watch for new modals being added to the document
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Check if the added node is a modal or contains modals
                        const modals = node.classList?.contains('modal-overlay') 
                            ? [node] 
                            : node.querySelectorAll?.('.modal-overlay') || [];
                            
                        modals.forEach(modal => {
                            if (!this.portal.contains(modal)) {
                                this.moveModalToPortal(modal);
                            }
                        });
                    }
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    moveModalToPortal(modal) {
        // Move single modal to portal
        modal.style.pointerEvents = 'auto';
        this.portal.appendChild(modal);
        this.watchModalVisibility(modal);
    }

    // Public method to manually move a modal
    addModal(modal) {
        if (modal && !this.portal.contains(modal)) {
            this.moveModalToPortal(modal);
        }
    }

    // Public method to show a modal (ensures proper z-index handling)
    showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            // Ensure modal is in portal
            this.addModal(modal);
            
            // Show modal
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            
            // Update portal pointer events
            this.updatePortalPointerEvents();
            
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }
    }

    // Public method to hide a modal
    hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            
            // Update portal pointer events
            this.updatePortalPointerEvents();
            
            // Restore body scroll if no modals are visible
            const visibleModals = this.portal.querySelectorAll('.modal-overlay:not(.hidden)');
            const hasVisibleModal = Array.from(visibleModals).some(m => 
                m.style.display !== 'none' && !m.classList.contains('hidden')
            );
            
            if (!hasVisibleModal) {
                document.body.style.overflow = '';
            }
        }
    }
}

// Initialize modal portal system
let modalPortal;

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        modalPortal = new ModalPortal();
    });
} else {
    modalPortal = new ModalPortal();
}

// Export for global use
window.ModalPortal = modalPortal;

// Enhanced modal functions for global use
window.showModal = function(modalId) {
    if (modalPortal) {
        modalPortal.showModal(modalId);
    }
};

window.hideModal = function(modalId) {
    if (modalPortal) {
        modalPortal.hideModal(modalId);
    }
};

export default ModalPortal;
