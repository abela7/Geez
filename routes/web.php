<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard.index');
});

Route::get('/admin/inventory', function () {
    return view('admin.inventory.index');
});

// Inventory Stock Levels Routes
Route::prefix('admin/inventory/stock-levels')->name('admin.inventory.stock-levels.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Inventory\StockLevelsController::class, 'index'])->name('index');
    Route::get('/{item}', [App\Http\Controllers\Admin\Inventory\StockLevelsController::class, 'show'])->name('show');
    Route::post('/{item}/update-stock', [App\Http\Controllers\Admin\Inventory\StockLevelsController::class, 'updateStock'])->name('update-stock');
    Route::post('/bulk-update', [App\Http\Controllers\Admin\Inventory\StockLevelsController::class, 'bulkUpdate'])->name('bulk-update');
    Route::get('/export', [App\Http\Controllers\Admin\Inventory\StockLevelsController::class, 'export'])->name('export');
});

// Inventory Ingredients Routes
Route::prefix('admin/inventory/ingredients')->name('admin.inventory.ingredients.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Inventory\IngredientsController::class, 'index'])->name('index');
    Route::get('/{ingredient}', [App\Http\Controllers\Admin\Inventory\IngredientsController::class, 'show'])->name('show');
    Route::post('/bulk-action', [App\Http\Controllers\Admin\Inventory\IngredientsController::class, 'bulkAction'])->name('bulk-action');
    Route::get('/export', [App\Http\Controllers\Admin\Inventory\IngredientsController::class, 'export'])->name('export');
});

// Inventory Settings Routes
Route::prefix('admin/inventory/settings')->name('admin.inventory.settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'index'])->name('index');
    
    // Categories
    Route::post('/categories', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'deleteCategory'])->name('categories.delete');
    
    // Units
    Route::post('/units', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'storeUnit'])->name('units.store');
    Route::put('/units/{unit}', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'updateUnit'])->name('units.update');
    Route::delete('/units/{unit}', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'deleteUnit'])->name('units.delete');
    
        // Types
        Route::post('/types', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'storeType'])->name('types.store');
        Route::put('/types/{type}', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'updateType'])->name('types.update');
        Route::delete('/types/{type}', [App\Http\Controllers\Admin\Inventory\InventorySettingsController::class, 'deleteType'])->name('types.delete');
    });

    // Recipes Routes
    Route::prefix('admin/inventory/recipes')->name('admin.inventory.recipes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'store'])->name('store');
        Route::get('/{recipe}', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'show'])->name('show');
        Route::get('/{recipe}/edit', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'edit'])->name('edit');
        Route::put('/{recipe}', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'update'])->name('update');
        Route::delete('/{recipe}', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'destroy'])->name('destroy');
        Route::post('/{recipe}/duplicate', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'duplicate'])->name('duplicate');
        Route::post('/{recipe}/calculate-costs', [App\Http\Controllers\Admin\Inventory\RecipesController::class, 'calculateCosts'])->name('calculate-costs');
    });

    // Movements Routes
    Route::prefix('admin/inventory/movements')->name('admin.inventory.movements.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Inventory\MovementsController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Inventory\MovementsController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\Inventory\MovementsController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\Admin\Inventory\MovementsController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\Inventory\MovementsController::class, 'destroy'])->name('destroy');
    });

    // Purchasing Routes
    Route::prefix('admin/inventory/purchasing')->name('admin.inventory.purchasing.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-received', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'markReceived'])->name('mark-received');
        Route::post('/{id}/cancel', [App\Http\Controllers\Admin\Inventory\PurchasingController::class, 'cancel'])->name('cancel');
    });

    // Locations Routes
    Route::prefix('admin/inventory/locations')->name('admin.inventory.locations.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/activate', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'activate'])->name('activate');
        Route::post('/{id}/deactivate', [App\Http\Controllers\Admin\Inventory\LocationsController::class, 'deactivate'])->name('deactivate');
    });

    // Suppliers Routes
    Route::prefix('admin/inventory/suppliers')->name('admin.inventory.suppliers.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/activate', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'activate'])->name('activate');
        Route::post('/{id}/deactivate', [App\Http\Controllers\Admin\Inventory\SuppliersController::class, 'deactivate'])->name('deactivate');
    });

// Alerts Routes (UI-only)
Route::get('/admin/inventory/alerts', function () {
    return view('admin.inventory.alerts.index');
});

// Analytics Routes (UI-only)
Route::get('/admin/inventory/analytics', function () {
    return view('admin.inventory.analytics.index');
});

// Stocktakes Routes (UI-only)
Route::get('/admin/inventory/stocktakes', function () {
    return view('admin.inventory.stocktakes.index');
});

Route::get('/admin/sales', function () {
    return view('admin.sales.index');
});

// Finance Routes
Route::get('/admin/finance/expenses', function () {
    return view('admin.finance.expenses');
});

Route::get('/admin/finance/budgeting', function () {
    return view('admin.finance.budgeting');
});

Route::get('/admin/finance/reports', function () {
    return view('admin.finance.reports');
});

Route::get('/admin/finance/settings', function () {
    return view('admin.finance.settings');
});

// Menu Management Routes
Route::get('/admin/menu/food-items', function () {
    return view('admin.menu.food-items');
});
Route::get('/admin/menu/categories', function () {
    return view('admin.menu.categories');
});
Route::get('/admin/menu/modifiers', function () {
    return view('admin.menu.modifiers');
});
Route::get('/admin/menu/dish-cost', function () {
    return view('admin.menu.dish-cost');
});
Route::get('/admin/menu/pricing', function () {
    return view('admin.menu.pricing');
});
Route::get('/admin/menu/design', function () {
    return view('admin.menu.design');
});

// Customer Management Routes
Route::get('/admin/customers/directory', function () {
    return view('admin.customers.directory');
});
Route::get('/admin/customers/loyalty', function () {
    return view('admin.customers.loyalty');
});
Route::get('/admin/customers/reservations', function () {
    return view('admin.customers.reservations');
});
Route::get('/admin/customers/analytics', function () {
    return view('admin.customers.analytics');
});
Route::get('/admin/customers/feedback', function () {
    return view('admin.customers.feedback');
});

// Reports Routes
Route::get('/admin/reports/sales', function () {
    return view('admin.reports.sales');
});
Route::get('/admin/reports/customers', function () {
    return view('admin.reports.customers');
});
Route::get('/admin/reports/menu', function () {
    return view('admin.reports.menu');
});
Route::get('/admin/reports/inventory', function () {
    return view('admin.reports.inventory');
});
Route::get('/admin/reports/staff', function () {
    return view('admin.reports.staff');
});
Route::get('/admin/reports/financial', function () {
    return view('admin.reports.financial');
});
Route::get('/admin/reports/operational', function () {
    return view('admin.reports.operational');
});
Route::get('/admin/reports/executive', function () {
    return view('admin.reports.executive');
});

// Table & Room Management Routes
Route::get('/admin/tables/rooms', function () {
    return view('admin.tables.rooms');
});
Route::get('/admin/tables/categories', function () {
    return view('admin.tables.categories');
});
Route::get('/admin/tables/types', function () {
    return view('admin.tables.types');
});
Route::get('/admin/tables/layout', function () {
    return view('admin.tables.layout');
});

// Staff Management Routes
Route::get('/admin/staff', function () {
    return view('admin.staff.index');
});

Route::get('/admin/staff/directory', function () {
    return view('admin.staff.directory');
});

Route::get('/admin/staff/performance', function () {
    return view('admin.staff.performance');
});

Route::get('/admin/staff/attendance', function () {
    return view('admin.staff.attendance');
});

Route::get('/admin/staff/tasks', function () {
    return view('admin.staff.tasks');
});

Route::get('/admin/staff/payroll', function () {
    return view('admin.staff.payroll');
});

Route::get('/admin/customers', function () {
    return view('admin.customers.index');
});

Route::get('/admin/reports', function () {
    return view('admin.reports.index');
});

Route::get('/admin/settings', function () {
    return view('admin.settings.index');
});

// Language switching routes
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'en|am|ti');

