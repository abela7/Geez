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

Route::get('/admin/sales', function () {
    return view('admin.sales.index');
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

