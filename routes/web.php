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

// Bar Management Routes
Route::prefix('admin/bar/inventory')->name('admin.bar.inventory.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'total_beverages' => 156,
            'low_stock_count' => 12,
            'out_of_stock_count' => 3,
            'total_value' => 15420.50
        ];
        
        $beverages = collect([
            (object) [
                'id' => 1,
                'name' => 'Johnnie Walker Black Label',
                'beverage_type' => 'spirits',
                'brand' => 'Johnnie Walker',
                'current_stock' => 8.5,
                'minimum_stock' => 5.0,
                'unit' => 'bottles',
                'storage_location' => 'spirit_cabinet',
                'abv' => 40,
                'barcode' => '5000267013200',
                'stock_status' => 'in_stock'
            ],
            (object) [
                'id' => 2,
                'name' => 'Heineken Beer',
                'beverage_type' => 'beer',
                'brand' => 'Heineken',
                'current_stock' => 2.0,
                'minimum_stock' => 10.0,
                'unit' => 'cases',
                'storage_location' => 'beer_cooler',
                'abv' => 5,
                'barcode' => '8712000000000',
                'stock_status' => 'low_stock'
            ],
            (object) [
                'id' => 3,
                'name' => 'Cabernet Sauvignon 2020',
                'beverage_type' => 'wine',
                'brand' => 'Kendall-Jackson',
                'current_stock' => 0.0,
                'minimum_stock' => 6.0,
                'unit' => 'bottles',
                'storage_location' => 'wine_cellar',
                'abv' => 13.5,
                'barcode' => '0123456789012',
                'stock_status' => 'out_of_stock'
            ]
        ]);
        
        return view('admin.bar.inventory.index', compact('stats', 'beverages'));
    })->name('index');
});
Route::prefix('admin/bar/recipes')->name('admin.bar.recipes.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'total_recipes' => 45,
            'signature_recipes' => 8,
            'popular_recipes' => 12,
            'avg_cost_per_drink' => 8.50
        ];
        
        $recipes = collect([
            (object) [
                'id' => 1,
                'name' => 'Classic Old Fashioned',
                'recipe_type' => 'classic_cocktail',
                'difficulty' => 'medium',
                'glass_type' => 'lowball',
                'preparation_time' => 3,
                'cost_per_drink' => 12.50,
                'ingredients_count' => 4,
                'description' => 'A timeless whiskey cocktail with sugar, bitters, and orange peel.',
            ],
            (object) [
                'id' => 2,
                'name' => 'Signature Martini',
                'recipe_type' => 'signature_cocktail',
                'difficulty' => 'easy',
                'glass_type' => 'martini',
                'preparation_time' => 2,
                'cost_per_drink' => 15.00,
                'ingredients_count' => 3,
                'description' => 'Our house special martini with premium gin and dry vermouth.',
            ],
            (object) [
                'id' => 3,
                'name' => 'Virgin Mojito',
                'recipe_type' => 'mocktail',
                'difficulty' => 'easy',
                'glass_type' => 'highball',
                'preparation_time' => 5,
                'cost_per_drink' => 6.50,
                'ingredients_count' => 5,
                'description' => 'Refreshing non-alcoholic mojito with mint, lime, and soda water.',
            ],
            (object) [
                'id' => 4,
                'name' => 'Flaming Shot',
                'recipe_type' => 'shot',
                'difficulty' => 'expert',
                'glass_type' => 'shot_glass',
                'preparation_time' => 2,
                'cost_per_drink' => 8.00,
                'ingredients_count' => 2,
                'description' => 'Spectacular flaming shot that requires expert handling.',
            ]
        ]);
        
        return view('admin.bar.recipes.index', compact('stats', 'recipes'));
    })->name('index');
});
Route::prefix('admin/bar/pricing')->name('admin.bar.pricing.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'total_drinks' => 45,
            'avg_price' => 12.50,
            'avg_margin' => 58.2,
            'happy_hour_status' => 'Active'
        ];
        
        $drinks = collect([
            (object) [
                'id' => 1,
                'name' => 'Johnnie Walker Black Label',
                'category' => 'spirits',
                'base_price' => 18.00,
                'happy_hour_price' => 14.40,
                'cost_price' => 8.50,
                'profit_margin' => 52.8,
                'markup_percentage' => 111.8,
                'active' => true
            ],
            (object) [
                'id' => 2,
                'name' => 'Heineken Beer',
                'category' => 'beer',
                'base_price' => 8.00,
                'happy_hour_price' => 6.40,
                'cost_price' => 3.50,
                'profit_margin' => 56.3,
                'markup_percentage' => 128.6,
                'active' => true
            ],
            (object) [
                'id' => 3,
                'name' => 'House Red Wine',
                'category' => 'wine',
                'base_price' => 12.00,
                'happy_hour_price' => 9.60,
                'cost_price' => 5.00,
                'profit_margin' => 58.3,
                'markup_percentage' => 140.0,
                'active' => true
            ],
            (object) [
                'id' => 4,
                'name' => 'Classic Martini',
                'category' => 'cocktails',
                'base_price' => 16.00,
                'happy_hour_price' => 12.80,
                'cost_price' => 6.50,
                'profit_margin' => 59.4,
                'markup_percentage' => 146.2,
                'active' => true
            ],
            (object) [
                'id' => 5,
                'name' => 'Virgin Mojito',
                'category' => 'mocktails',
                'base_price' => 9.00,
                'happy_hour_price' => 7.20,
                'cost_price' => 3.00,
                'profit_margin' => 66.7,
                'markup_percentage' => 200.0,
                'active' => true
            ]
        ]);
        
        return view('admin.bar.pricing.index', compact('stats', 'drinks'));
    })->name('index');
});
Route::prefix('admin/bar/analytics')->name('admin.bar.analytics.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'total_revenue' => 15420.50,
            'drinks_sold' => 1247,
            'avg_order_value' => 28.75,
            'peak_hour' => '8 PM',
            'revenue_change' => 12.5,
            'drinks_change' => 8.3,
            'aov_change' => -2.1
        ];
        
        $analytics = [
            'sales_by_category' => [
                ['category' => 'Cocktails', 'sales' => 8500, 'percentage' => 55.1],
                ['category' => 'Beer', 'sales' => 3200, 'percentage' => 20.8],
                ['category' => 'Wine', 'sales' => 2400, 'percentage' => 15.6],
                ['category' => 'Spirits', 'sales' => 900, 'percentage' => 5.8],
                ['category' => 'Mocktails', 'sales' => 420, 'percentage' => 2.7]
            ],
            'popular_drinks' => [
                ['name' => 'Classic Martini', 'sales' => 156, 'revenue' => 2496],
                ['name' => 'Heineken Beer', 'sales' => 142, 'revenue' => 1136],
                ['name' => 'House Red Wine', 'sales' => 98, 'revenue' => 1176],
                ['name' => 'Old Fashioned', 'sales' => 87, 'revenue' => 1566],
                ['name' => 'Virgin Mojito', 'sales' => 76, 'revenue' => 684]
            ],
            'insights' => [
                'top_selling_drink' => 'Classic Martini',
                'most_profitable' => 'Virgin Mojito',
                'happy_hour_impact' => '+35%',
                'inventory_turnover' => '4.2x'
            ]
        ];
        
        return view('admin.bar.analytics.index', compact('stats', 'analytics'));
    })->name('index');
});
Route::prefix('admin/bar/suppliers')->name('admin.bar.suppliers.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'total_suppliers' => 8,
            'active_suppliers' => 7,
            'total_orders' => 119,
            'avg_rating' => 4.6
        ];
        
        $suppliers = collect([
            (object) [
                'id' => 1,
                'name' => 'Premium Wine Distributors',
                'specialty' => 'wine_distributor',
                'contact_person' => 'Sarah Johnson',
                'phone_number' => '+1-555-0123',
                'email_address' => 'sarah@premiumwines.com',
                'website' => 'https://premiumwines.com',
                'address' => '123 Wine Street, Napa Valley, CA',
                'payment_terms' => 'net_30',
                'delivery_days' => 3,
                'minimum_order' => 500.00,
                'delivery_rating' => 4.8,
                'quality_rating' => 4.9,
                'price_rating' => 4.2,
                'last_order_date' => '2024-01-15',
                'total_orders' => 24,
                'average_delivery_time' => 2.5,
                'active' => true
            ],
            (object) [
                'id' => 2,
                'name' => 'Craft Beer Supply Co.',
                'specialty' => 'beer_distributor',
                'contact_person' => 'Mike Rodriguez',
                'phone_number' => '+1-555-0456',
                'email_address' => 'mike@craftbeersupply.com',
                'website' => 'https://craftbeersupply.com',
                'address' => '456 Brewery Lane, Portland, OR',
                'payment_terms' => 'net_15',
                'delivery_days' => 2,
                'minimum_order' => 300.00,
                'delivery_rating' => 4.9,
                'quality_rating' => 4.7,
                'price_rating' => 4.5,
                'last_order_date' => '2024-01-20',
                'total_orders' => 18,
                'average_delivery_time' => 1.8,
                'active' => true
            ],
            (object) [
                'id' => 3,
                'name' => 'Elite Spirits International',
                'specialty' => 'spirits_distributor',
                'contact_person' => 'James Wilson',
                'phone_number' => '+1-555-0789',
                'email_address' => 'james@elitespirits.com',
                'website' => 'https://elitespirits.com',
                'address' => '789 Distillery Road, Louisville, KY',
                'payment_terms' => 'net_30',
                'delivery_days' => 5,
                'minimum_order' => 1000.00,
                'delivery_rating' => 4.6,
                'quality_rating' => 4.8,
                'price_rating' => 3.9,
                'last_order_date' => '2024-01-10',
                'total_orders' => 32,
                'average_delivery_time' => 4.2,
                'active' => true
            ],
            (object) [
                'id' => 4,
                'name' => 'Fresh Coffee Roasters',
                'specialty' => 'coffee_supplier',
                'contact_person' => 'Lisa Chen',
                'phone_number' => '+1-555-0321',
                'email_address' => 'lisa@freshcoffee.com',
                'website' => 'https://freshcoffee.com',
                'address' => '321 Roast Avenue, Seattle, WA',
                'payment_terms' => 'net_15',
                'delivery_days' => 1,
                'minimum_order' => 150.00,
                'delivery_rating' => 5.0,
                'quality_rating' => 4.9,
                'price_rating' => 4.6,
                'last_order_date' => '2024-01-25',
                'total_orders' => 45,
                'average_delivery_time' => 1.2,
                'active' => true
            ]
        ]);
        
        return view('admin.bar.suppliers.index', compact('stats', 'suppliers'));
    })->name('index');
});
Route::prefix('admin/bar/settings')->name('admin.bar.settings.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $conversionRates = [
            'standard_shot_size' => 25,
            'double_shot_size' => 50,
            'wine_pour_size' => 150,
            'beer_pour_size' => 330,
            'pint_to_glasses' => 80,
            'gallon_to_pints' => 8,
            'keg_to_pints' => 124,
            'bottle_to_singles' => 30,
            'bottle_to_doubles' => 15,
            'liter_to_singles' => 40,
            'wine_bottle_to_glasses' => 5,
            'wine_case_to_bottles' => 12
        ];
        
        $thresholds = [
            'beer_threshold' => 3,
            'spirits_threshold' => 10,
            'wine_threshold' => 2,
            'mixers_threshold' => 20
        ];
        
        $settings = [
            'bar_name' => 'Main Bar',
            'default_markup' => 150,
            'last_call_time' => '23:30',
            'happy_hour_enabled' => true,
            'age_verification' => true,
            'stock_rotation' => true,
            'waste_tracking' => false,
            'auto_inventory_update' => true,
            'real_time_sync' => false,
            'alert_frequency' => 'daily',
            'alert_methods' => ['dashboard', 'email']
        ];
        
        return view('admin.bar.settings.index', compact('conversionRates', 'thresholds', 'settings'));
    })->name('index');
});

// Tip Management Routes (Finance)
Route::prefix('admin/finance/tips')->name('admin.finance.tips.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'total_tips_today' => 245.75,
            'pending_distribution' => 68.25,
            'distributed_today' => 177.50,
            'avg_per_staff' => 35.50,
            'tips_change' => 15.3,
            'pending_count' => 3,
            'staff_count' => 5
        ];
        
        $tipRules = [
            'current_rule' => 'shared',
            'direct' => ['receiver' => 100],
            'shared' => ['front_of_house' => 60, 'kitchen' => 40],
            'custom' => ['servers' => 45, 'bartenders' => 25, 'kitchen' => 20, 'management' => 10]
        ];
        
        return view('admin.finance.tips.index', compact('stats', 'tipRules'));
    })->name('index');
});

// Injera Management Routes
Route::prefix('admin/injera')->name('admin.injera.')->group(function () {
    Route::get('/', function () {
        // Mock data for demonstration
        $stats = [
            'daily_production' => 43,
            'injera_remaining' => 12,
            'flour_efficiency' => 4.2,
            'production_trend' => 8.5,
            'estimated_hours' => 6
        ];
        
        $inventory = [
            'teff_flour' => ['amount' => 25.5, 'cost_per_kg' => 8.50, 'status' => 'in_stock'],
            'wheat_flour' => ['amount' => 3.2, 'cost_per_kg' => 2.80, 'status' => 'low_stock'],
            'water' => ['amount' => 50, 'cost_per_kg' => 0.01, 'status' => 'in_stock']
        ];
        
        $batches = [
            [
                'id' => 1,
                'batch_id' => 'INJ-001',
                'status' => 'baking',
                'current_step' => 4,
                'teff_amount' => 10,
                'wheat_amount' => 2,
                'expected_yield' => 45,
                'baker' => 'Almaz Tadesse',
                'start_date' => '2024-01-13T08:00:00',
                'completion_estimate' => '2024-01-16T16:00:00'
            ],
            [
                'id' => 2,
                'batch_id' => 'INJ-002', 
                'status' => 'fermentation',
                'current_step' => 3,
                'teff_amount' => 8,
                'wheat_amount' => 1.5,
                'expected_yield' => 35,
                'baker' => null,
                'start_date' => '2024-01-14T09:00:00',
                'completion_estimate' => '2024-01-17T12:00:00'
            ]
        ];
        
        $salesAnalysis = [
            'food_service_usage' => 28,
            'direct_sales' => 12,
            'waste_count' => 3,
            'efficiency_rate' => 91.2,
            'revenue_today' => 36.00,
            'waste_cost' => 6.03,
            'recommendation' => 'GOOD'
        ];
        
        return view('admin.injera.index', compact('stats', 'inventory', 'batches', 'salesAnalysis'));
    })->name('index');
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

// Injera Management Routes
Route::prefix('admin/injera')->name('admin.injera.')->group(function () {
    // Flour Management Routes
    Route::prefix('flour-management')->name('flour-management.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Injera\FlourManagementController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Injera\FlourManagementController::class, 'store'])->name('store');
        Route::put('/{flour}', [App\Http\Controllers\Admin\Injera\FlourManagementController::class, 'update'])->name('update');
        Route::delete('/{flour}', [App\Http\Controllers\Admin\Injera\FlourManagementController::class, 'destroy'])->name('destroy');
        Route::post('/update-stock', [App\Http\Controllers\Admin\Injera\FlourManagementController::class, 'updateStock'])->name('update-stock');
    });
    
    // Bucket Configurations Routes
    Route::prefix('bucket-configurations')->name('bucket-configurations.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Injera\BucketConfigurationsController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Injera\BucketConfigurationsController::class, 'store'])->name('store');
        Route::put('/{bucket}', [App\Http\Controllers\Admin\Injera\BucketConfigurationsController::class, 'update'])->name('update');
        Route::delete('/{bucket}', [App\Http\Controllers\Admin\Injera\BucketConfigurationsController::class, 'destroy'])->name('destroy');
        Route::post('/{bucket}/duplicate', [App\Http\Controllers\Admin\Injera\BucketConfigurationsController::class, 'duplicate'])->name('duplicate');
    });
    
    // Production Batches Routes
    Route::prefix('production-batches')->name('production-batches.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Injera\ProductionBatchesController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Admin\Injera\ProductionBatchesController::class, 'store'])->name('store');
        Route::post('/{batch}/update-stage', [App\Http\Controllers\Admin\Injera\ProductionBatchesController::class, 'updateStage'])->name('update-stage');
        Route::post('/{batch}/complete', [App\Http\Controllers\Admin\Injera\ProductionBatchesController::class, 'complete'])->name('complete');
        Route::post('/{batch}/cancel', [App\Http\Controllers\Admin\Injera\ProductionBatchesController::class, 'cancel'])->name('cancel');
    });
    
    // Injera Stock Levels Routes
    Route::prefix('injera-stock-levels')->name('injera-stock-levels.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\Injera\InjeraStockLevelsController::class, 'index'])->name('index');
        Route::put('/', [App\Http\Controllers\Admin\Injera\InjeraStockLevelsController::class, 'update'])->name('update');
        Route::post('/add-stock', [App\Http\Controllers\Admin\Injera\InjeraStockLevelsController::class, 'addStock'])->name('add-stock');
        Route::post('/reserve-stock', [App\Http\Controllers\Admin\Injera\InjeraStockLevelsController::class, 'reserveStock'])->name('reserve-stock');
    });
});

