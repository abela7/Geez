import './bootstrap';

// Import Alpine.js
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Expose Alpine globally
window.Alpine = Alpine;

// Tell Livewire to wait for our Alpine init (prevents double-start warning)
window.deferLoadingAlpine = (callback) => {
  document.addEventListener('alpine:initialized', callback);
};

// Register plugins before Alpine starts
Alpine.plugin(persist);

// Start Alpine (single source of truth)
Alpine.start();
