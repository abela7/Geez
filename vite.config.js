import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/admin/layout.css',
                'resources/js/admin/layout.js',
                'resources/css/admin/staff.css',
                'resources/js/admin/staff.js',
                'resources/css/admin/staff-performance.css',
                'resources/js/admin/staff-performance.js',
                'resources/css/admin/staff-attendance.css',
                'resources/js/admin/staff-attendance.js',
            'resources/css/admin/inventory-stock-levels.css',
            'resources/js/admin/inventory-stock-levels.js',
            'resources/css/admin/inventory-ingredients.css',
            'resources/js/admin/inventory-ingredients.js',
            'resources/css/admin/inventory-settings.css',
            'resources/js/admin/inventory-settings.js',
            'resources/css/admin/inventory-recipes.css',
            'resources/js/admin/inventory-recipes.js',
            'resources/css/admin/inventory-movements.css',
            'resources/js/admin/inventory-movements.js'
            ],
            refresh: true,
        }),
    ],
});
