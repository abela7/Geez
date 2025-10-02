/**
 * Global Alpine.js Components Registration
 * This file registers all Alpine components before Alpine starts
 */

// Import component definitions
import { shiftCreateComponent } from './shifts/create-component.js';

// Register components when Alpine initializes
document.addEventListener('alpine:init', () => {
    // Register Shift Create component
    Alpine.data('shiftCreateData', shiftCreateComponent);
    
    console.log('Alpine components registered');
});

