/**
 * Shift Create Form JavaScript
 * Handles form validation, calculations, and user interactions
 */

// Component is registered globally in resources/js/app.js

// Initialize notification styles when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Shift Create JS loaded');
    
    // Add notification styles if not already present
    if (!document.querySelector('#notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
                animation: slideIn 0.3s ease-out;
                font-weight: 500;
                color: white;
            }
            
            .notification-success {
                background: #10B981;
            }
            
            .notification-error {
                background: #EF4444;
            }
            
            .notification-info {
                background: #3B82F6;
            }
            
            .notification-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 1rem;
            }
            
            .notification-close {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                opacity: 0.7;
                transition: opacity 0.2s;
                color: white;
            }
            
            .notification-close:hover {
                opacity: 1;
            }
            
            .form-loading {
                pointer-events: none;
                opacity: 0.7;
                position: relative;
            }
            
            .form-loading::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(255, 255, 255, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 1000;
            }
            
            .form-loading::before {
                content: 'Creating shift...';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 1001;
                background: #3B82F6;
                color: white;
                padding: 1rem 2rem;
                border-radius: 0.5rem;
                font-weight: 500;
            }
            
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
});
