import './bootstrap';

// Defer Alpine's start until Livewire initializes to avoid multiple instances
// This MUST be defined BEFORE importing Alpine
window.deferLoadingAlpine = function(callback) {
  window.addEventListener('livewire:init', callback);
};

// Import Alpine.js
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Expose Alpine globally so Livewire detects it and doesn't inject its own
window.Alpine = Alpine;

// Register plugins before Alpine starts
Alpine.plugin(persist);

// Start Alpine (Livewire will trigger the deferred start)
Alpine.start();
