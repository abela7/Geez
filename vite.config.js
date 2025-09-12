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
            'resources/js/admin/inventory-movements.js',
            'resources/css/admin/inventory-purchasing.css',
            'resources/js/admin/inventory-purchasing.js',
            'resources/css/admin/inventory-locations.css',
            'resources/js/admin/inventory-locations.js',
            'resources/css/admin/injera/flour-management.css',
            'resources/js/admin/injera/flour-management.js',
            'resources/css/admin/injera/bucket-configurations.css',
            'resources/js/admin/injera/bucket-configurations.js',
            'resources/css/admin/injera/production-batches.css',
            'resources/js/admin/injera/production-batches.js',
            'resources/css/admin/injera/injera-stock-levels.css',
            'resources/js/admin/injera/injera-stock-levels.js',
            'resources/css/admin/injera/cost-analysis.css',
            'resources/js/admin/injera/cost-analysis.js',
            'resources/css/admin/injera/orders.css',
            'resources/js/admin/injera/orders.js'
            ],
            refresh: true,
        }),
    ],
});
