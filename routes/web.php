<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Default Laravel login route (required by Laravel Auth system)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});

// Protected Admin Routes
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    // Inventory Management Routes (your actual pages)
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('index');
        Route::get('/stock-levels', [\App\Http\Controllers\Admin\InventoryStockLevelsController::class, 'index'])->name('stock-levels.index');
        Route::get('/ingredients', [\App\Http\Controllers\Admin\InventoryIngredientsController::class, 'index'])->name('ingredients.index');
        Route::get('/settings', function () {
            return view('admin.inventory.settings.index');
        })->name('settings.index');
        Route::get('/recipes', function () {
            return view('admin.inventory.recipes.index');
        })->name('recipes.index');
        Route::get('/movements', function () {
            return view('admin.inventory.movements.index');
        })->name('movements.index');
        Route::get('/locations', function () {
            return view('admin.inventory.locations.index');
        })->name('locations.index');
        Route::get('/purchasing', function () {
            return view('admin.inventory.purchasing.index');
        })->name('purchasing.index');
        Route::get('/suppliers', function () {
            return view('admin.inventory.suppliers.index');
        })->name('suppliers.index');
        Route::get('/alerts', function () {
            return view('admin.inventory.alerts.index');
        })->name('alerts.index');
        Route::get('/analytics', function () {
            return view('admin.inventory.analytics.index');
        })->name('analytics.index');
        Route::get('/stocktakes', function () {
            return view('admin.inventory.stocktakes.index');
        })->name('stocktakes.index');
    });

    // Sales & Finance Routes (your actual pages)
    Route::get('/sales', function () {
        return view('admin.sales.index');
    })->name('sales.index');
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/tips', function () {
            return view('admin.finance.tips.index');
        })->name('tips.index');
        Route::get('/expenses', function () {
            return view('admin.finance.expenses');
        })->name('expenses.index');
        Route::get('/budgeting', function () {
            return view('admin.finance.budgeting');
        })->name('budgeting.index');
        Route::get('/reports', function () {
            return view('admin.finance.reports');
        })->name('reports.index');
        Route::get('/settings', function () {
            return view('admin.finance.settings');
        })->name('settings.index');
    });

    // Staff Management Routes
    Route::prefix('staff')->name('staff.')->group(function () {
        // Other Staff Modules (your actual pages) - define BEFORE resource to avoid being captured by {staff}
        Route::get('/directory', [\App\Http\Controllers\Admin\StaffDirectoryController::class, 'index'])->name('directory.index');
        Route::get('/performance', [\App\Http\Controllers\Admin\StaffPerformanceController::class, 'index'])->name('performance.index');
        // Staff Attendance
        Route::get('/attendance', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'index'])->name('attendance.index');
        Route::get('/attendance/{staffAttendance}', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'show'])->name('attendance.show');
        Route::post('/attendance', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'store'])->name('attendance.store');
        Route::put('/attendance/{staffAttendance}', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'update'])->name('attendance.update');
        Route::delete('/attendance/{staffAttendance}', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'destroy'])->name('attendance.destroy');
        
        // Enhanced Attendance - State Machine API Routes
        Route::post('/attendance/{staffAttendance}/start-break', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'startBreak'])->name('attendance.start_break');
        Route::post('/attendance/{staffAttendance}/resume-work', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'resumeWork'])->name('attendance.resume_work');
        Route::post('/attendance/{staffAttendance}/clock-out', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'clockOut'])->name('attendance.clock_out');
        Route::post('/attendance/{staffAttendance}/auto-close', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'autoClose'])->name('attendance.auto_close');
        
        // Attendance Intervals Management
        Route::get('/attendance/{staffAttendance}/intervals', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'getIntervals'])->name('attendance.intervals');
        Route::post('/attendance/intervals/{interval}/approve', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'approveInterval'])->name('attendance.intervals.approve');
        
        // Real-time API endpoints
        Route::get('/attendance/dashboard-data', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'getDashboardData'])->name('attendance.dashboard_data');
        Route::get('/attendance/staff/{staff}/active-session', [\App\Http\Controllers\Admin\StaffAttendanceController::class, 'getActiveSession'])->name('attendance.active_session');

        // Staff Tasks (Simple Implementation)
        Route::get('/tasks', [\App\Http\Controllers\Admin\StaffTasksController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/today', [\App\Http\Controllers\Admin\StaffTasksController::class, 'today'])->name('tasks.today');
        Route::get('/tasks/create', [\App\Http\Controllers\Admin\StaffTasksController::class, 'create'])->name('tasks.create');

        // Task Settings Management (MUST be before parameterized routes)
        Route::get('/tasks/settings', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'index'])->name('tasks.settings.index');
        Route::post('/tasks/settings/types', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'storeTaskType'])->name('tasks.settings.types.store');
        Route::get('/tasks/settings/types/{taskType}/edit', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'editTaskType'])->name('tasks.settings.types.edit');
        Route::put('/tasks/settings/types/{taskType}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'updateTaskType'])->name('tasks.settings.types.update');
        Route::delete('/tasks/settings/types/{taskType}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'destroyTaskType'])->name('tasks.settings.types.destroy');
        Route::post('/tasks/settings/priorities', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'storeTaskPriority'])->name('tasks.settings.priorities.store');
        Route::get('/tasks/settings/priorities/{taskPriority}/edit', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'editTaskPriority'])->name('tasks.settings.priorities.edit');
        Route::put('/tasks/settings/priorities/{taskPriority}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'updateTaskPriority'])->name('tasks.settings.priorities.update');
        Route::delete('/tasks/settings/priorities/{taskPriority}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'destroyTaskPriority'])->name('tasks.settings.priorities.destroy');
        Route::post('/tasks/settings/categories', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'storeTaskCategory'])->name('tasks.settings.categories.store');
        Route::get('/tasks/settings/categories/{taskCategory}/edit', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'editTaskCategory'])->name('tasks.settings.categories.edit');
        Route::put('/tasks/settings/categories/{taskCategory}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'updateTaskCategory'])->name('tasks.settings.categories.update');
        Route::delete('/tasks/settings/categories/{taskCategory}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'destroyTaskCategory'])->name('tasks.settings.categories.destroy');
        Route::post('/tasks/settings/tags', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'storeTaskTag'])->name('tasks.settings.tags.store');
        Route::post('/tasks/settings/tags/ajax', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'storeTagAjax'])->name('tasks.settings.tags.ajax.store');
        Route::get('/tasks/settings/tags/{taskTag}/edit', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'editTaskTag'])->name('tasks.settings.tags.edit');
        Route::put('/tasks/settings/tags/{taskTag}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'updateTaskTag'])->name('tasks.settings.tags.update');
        Route::delete('/tasks/settings/tags/{taskTag}', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'destroyTaskTag'])->name('tasks.settings.tags.destroy');
        Route::post('/tasks/settings/reorder', [\App\Http\Controllers\Admin\TaskSettingsController::class, 'reorder'])->name('tasks.settings.reorder');

        // Parameterized task routes (MUST be after specific routes)
        Route::post('/tasks', [\App\Http\Controllers\Admin\StaffTasksController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}', [\App\Http\Controllers\Admin\StaffTasksController::class, 'show'])->name('tasks.show');
        Route::get('/tasks/{task}/edit', [\App\Http\Controllers\Admin\StaffTasksController::class, 'edit'])->name('tasks.edit');
        Route::put('/tasks/{task}', [\App\Http\Controllers\Admin\StaffTasksController::class, 'update'])->name('tasks.update');
        Route::get('/tasks/{task}/modal', [\App\Http\Controllers\Admin\StaffTasksController::class, 'modal'])->name('tasks.modal');
        Route::post('/tasks/bulk-action', [\App\Http\Controllers\Admin\StaffTasksController::class, 'bulkAction'])->name('tasks.bulk-action');
        Route::delete('/tasks/{task}', [\App\Http\Controllers\Admin\StaffTasksController::class, 'destroy'])->name('tasks.destroy');
        Route::put('/task-assignments/{assignment}/status', [\App\Http\Controllers\Admin\StaffTasksController::class, 'updateAssignmentStatus'])->name('task-assignments.update-status');
        Route::put('/task-assignments/{assignment}/quality-rating', [\App\Http\Controllers\Admin\StaffTasksController::class, 'updateQualityRating'])->name('task-assignments.update-quality-rating');
        Route::put('/tasks/{task}/status', [\App\Http\Controllers\Admin\StaffTasksController::class, 'updateTaskStatus'])->name('tasks.update-status');

        Route::get('/payroll', [\App\Http\Controllers\Admin\StaffPayrollController::class, 'index'])->name('payroll.index');

        // Payroll Management Routes
        Route::prefix('payroll')->name('payroll.')->group(function () {
            Route::get('/settings', [\App\Http\Controllers\Admin\PayrollController::class, 'settings'])->name('settings');
            Route::get('/periods', [\App\Http\Controllers\Admin\PayrollController::class, 'periods'])->name('periods');
            Route::get('/periods/{period}/records', [\App\Http\Controllers\Admin\PayrollController::class, 'records'])->name('periods.records');
            Route::get('/add', [\App\Http\Controllers\Admin\PayrollController::class, 'add'])->name('add');
            Route::get('/periods/{period}/review', [\App\Http\Controllers\Admin\PayrollController::class, 'review'])->name('review');
            Route::get('/periods/{period}/payment', [\App\Http\Controllers\Admin\PayrollController::class, 'payment'])->name('payment');
            Route::get('/periods/{period}/reports', [\App\Http\Controllers\Admin\PayrollController::class, 'reports'])->name('reports');
        });

        // Staff Profile - must be before resource routes
        Route::get('/profile/{staff}', [\App\Http\Controllers\Admin\StaffController::class, 'show'])->name('profile');
        // Staff Settings
        Route::get('settings', [\App\Http\Controllers\Admin\StaffSettingsController::class, 'index'])->name('settings.index');

        // Staff Types CRUD (MUST be before base resource to prevent {staff} catching "types")
        Route::resource('types', \App\Http\Controllers\Admin\StaffTypesController::class, ['parameters' => ['types' => 'staffType']]);
        Route::get('types/trashed/list', [\App\Http\Controllers\Admin\StaffTypesController::class, 'trashed'])->name('types.trashed');
        Route::patch('types/{id}/restore', [\App\Http\Controllers\Admin\StaffTypesController::class, 'restore'])->name('types.restore');
        Route::delete('types/{id}/force-delete', [\App\Http\Controllers\Admin\StaffTypesController::class, 'forceDelete'])->name('types.force-delete');
        Route::patch('types/{staffType}/toggle-active', [\App\Http\Controllers\Admin\StaffTypesController::class, 'toggleActive'])->name('types.toggle-active');

        // Staff CRUD (place AFTER static routes)
        Route::resource('/', \App\Http\Controllers\Admin\StaffController::class, ['parameters' => ['' => 'staff']]);
        Route::get('trashed', [\App\Http\Controllers\Admin\StaffController::class, 'trashed'])->name('trashed');
        Route::patch('{id}/restore', [\App\Http\Controllers\Admin\StaffController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [\App\Http\Controllers\Admin\StaffController::class, 'forceDelete'])->name('force-delete');
        Route::patch('{staff}/toggle-status', [\App\Http\Controllers\Admin\StaffController::class, 'toggleStatus'])->name('toggle-status');
    });

    // Shift Scheduling System Routes
    Route::prefix('shifts')->name('shifts.')->group(function () {

        // Shift templates management
        Route::resource('templates', \App\Http\Controllers\Admin\Shifts\TemplatesController::class, ['as' => 'shifts']);
        Route::post('/templates/{id}/duplicate', [\App\Http\Controllers\Admin\Shifts\TemplatesController::class, 'duplicate'])->name('shifts.templates.duplicate');
        Route::post('/templates/{id}/apply', [\App\Http\Controllers\Admin\Shifts\TemplatesController::class, 'apply'])->name('shifts.templates.apply');
        Route::post('/templates/{id}/preview', [\App\Http\Controllers\Admin\Shifts\TemplatesController::class, 'preview'])->name('shifts.templates.preview');
        Route::post('/templates/{id}/set-default', [\App\Http\Controllers\Admin\Shifts\TemplatesController::class, 'setDefault'])->name('shifts.templates.set-default');
        Route::post('/templates/{id}/toggle-active', [\App\Http\Controllers\Admin\Shifts\TemplatesController::class, 'toggleActive'])->name('shifts.templates.toggle-active');

        // Weekly Rota/Assignments Interface (Must be BEFORE resource route to avoid conflicts)
        Route::get('/assignments/staff-availability', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'getStaffAvailability'])->name('assignments.staff-availability');
        Route::post('/assignments/bulk-assign', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'bulkAssign'])->name('assignments.bulk-assign');

        // Weekly Rota Template Routes
        Route::post('/assignments/save-as-template', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'saveAsTemplate'])->name('assignments.save-template');
        Route::post('/assignments/apply-template', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'applyTemplate'])->name('assignments.apply-template');
        Route::get('/assignments/templates', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'getTemplates'])->name('assignments.get-templates');
        
        // Bulk Actions
        Route::post('/assignments/clear-week', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'clearWeek'])->name('assignments.clear-week');
        
        Route::get('/assignments', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'index'])->name('assignments.index');
        Route::post('/assignments', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'store'])->name('assignments.store');
        Route::put('/assignments/{assignment}', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'update'])->name('assignments.update');
        Route::delete('/assignments/{assignment}', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'destroy'])->name('assignments.destroy');
        Route::get('/assignments/{assignment}', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'show'])->name('assignments.show');
        Route::post('/assignments/{assignment}/exceptions', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'addException'])->name('assignments.exceptions.store');
        Route::post('/assignments/{assignment}/replace', [\App\Http\Controllers\Admin\ShiftsAssignmentsController::class, 'replaceStaff'])->name('assignments.replace');

        // Assignment management (Resource routes for API/CRUD - different from weekly rota UI)
        Route::resource('assignment-records', \App\Http\Controllers\Admin\StaffShiftAssignmentController::class, ['as' => 'shifts']);

        // Legacy UI routes (keep for backward compatibility)
        Route::get('/overview', [\App\Http\Controllers\Admin\ShiftsOverviewController::class, 'index'])->name('overview.index');
        Route::get('/manage', [\App\Http\Controllers\Admin\ShiftsManageController::class, 'index'])->name('manage.index');
        Route::get('/manage/create', [\App\Http\Controllers\Admin\ShiftsManageController::class, 'create'])->name('manage.create');
        Route::post('/manage', [\App\Http\Controllers\Admin\ShiftsManageController::class, 'store'])->name('manage.store');
        Route::get('/manage/{shift}/edit', [\App\Http\Controllers\Admin\ShiftsManageController::class, 'edit'])->name('manage.edit');
        Route::put('/manage/{shift}', [\App\Http\Controllers\Admin\ShiftsManageController::class, 'update'])->name('manage.update');
        Route::delete('/manage/{shift}', [\App\Http\Controllers\Admin\ShiftsManageController::class, 'destroy'])->name('manage.destroy');

        // Bulk actions for manage
        Route::patch('manage/bulk-activate', [\App\Http\Controllers\Admin\Shifts\ManageController::class, 'bulkActivate'])->name('manage.bulk-activate');
        Route::patch('manage/bulk-deactivate', [\App\Http\Controllers\Admin\Shifts\ManageController::class, 'bulkDeactivate'])->name('manage.bulk-deactivate');
        Route::delete('manage/bulk-delete', [\App\Http\Controllers\Admin\Shifts\ManageController::class, 'bulkDelete'])->name('manage.bulk-delete');

        // Weekly Schedule Management Routes
        Route::prefix('weekly-schedule')->name('weekly-schedule.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'index'])->name('index');
            Route::get('/{weeklySchedule}', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'show'])->name('show');
            Route::post('/{weeklySchedule}/apply-template', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'applyTemplate'])->name('apply-template');
            Route::post('/{weeklySchedule}/publish', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'publish'])->name('publish');
            Route::post('/{weeklySchedule}/archive', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'archive'])->name('archive');
            Route::post('/{weeklySchedule}/assignments', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'createAssignment'])->name('create-assignment');
            Route::delete('/{weeklySchedule}/assignments/{assignmentId}', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'removeAssignment'])->name('remove-assignment');
            Route::post('/{weeklySchedule}/copy-from-week', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'copyFromWeek'])->name('copy-from-week');
            Route::get('/{weeklySchedule}/assignment-options', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'getAssignmentOptions'])->name('assignment-options');
            Route::get('/statistics', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'statistics'])->name('statistics');
            Route::post('/auto-create-upcoming', [\App\Http\Controllers\Admin\WeeklyScheduleController::class, 'autoCreateUpcoming'])->name('auto-create-upcoming');
        });
    });

    // Menu Management Routes (your actual pages)
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/food-items', function () {
            return view('admin.menu.food-items');
        })->name('food-items.index');
        Route::get('/categories', function () {
            return view('admin.menu.categories');
        })->name('categories.index');
        Route::get('/modifiers', function () {
            return view('admin.menu.modifiers');
        })->name('modifiers.index');
        Route::get('/dish-cost', function () {
            return view('admin.menu.dish-cost');
        })->name('dish-cost.index');
        Route::get('/pricing', function () {
            return view('admin.menu.pricing');
        })->name('pricing.index');
        Route::get('/design', function () {
            return view('admin.menu.design');
        })->name('design.index');
    });

    // Customer Management Routes (your actual pages)
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/directory', function () {
            return view('admin.customers.directory');
        })->name('directory.index');
        Route::get('/loyalty', function () {
            return view('admin.customers.loyalty');
        })->name('loyalty.index');
        Route::get('/reservations', function () {
            return view('admin.customers.reservations');
        })->name('reservations.index');
        Route::get('/analytics', function () {
            return view('admin.customers.analytics');
        })->name('analytics.index');
        Route::get('/feedback', function () {
            return view('admin.customers.feedback');
        })->name('feedback.index');
    });

    // Table & Room Management Routes
    Route::prefix('tables')->name('tables.')->group(function () {
        Route::get('/rooms', function () {
            return view('admin.tables.rooms');
        })->name('rooms.index');
        Route::get('/categories', function () {
            return view('admin.tables.categories');
        })->name('categories.index');
        Route::get('/types', function () {
            return view('admin.tables.types');
        })->name('types.index');
        Route::get('/layout', function () {
            return view('admin.tables.layout');
        })->name('layout.index');
    });

    // Bar Management Routes
    Route::prefix('bar')->name('bar.')->group(function () {
        Route::get('/inventory', function () {
            return view('admin.bar.inventory.index');
        })->name('inventory.index');
        Route::get('/recipes', function () {
            return view('admin.bar.recipes.index');
        })->name('recipes.index');
        Route::get('/pricing', function () {
            return view('admin.bar.pricing.index');
        })->name('pricing.index');
        Route::get('/analytics', function () {
            return view('admin.bar.analytics.index');
        })->name('analytics.index');
        Route::get('/suppliers', function () {
            return view('admin.bar.suppliers.index');
        })->name('suppliers.index');
        Route::get('/settings', function () {
            return view('admin.bar.settings.index');
        })->name('settings.index');
    });

    // Injera Management Routes
    Route::prefix('injera')->name('injera.')->group(function () {
        Route::get('/', function () {
            return view('admin.injera.index');
        })->name('index');
        Route::get('/flour-management', function () {
            return view('admin.injera.flour-management.index');
        })->name('flour-management.index');
        Route::get('/bucket-configurations', function () {
            return view('admin.injera.bucket-configurations.index');
        })->name('bucket-configurations.index');
        Route::get('/production-batches', function () {
            return view('admin.injera.production-batches.index');
        })->name('production-batches.index');
        Route::get('/injera-stock-levels', function () {
            return view('admin.injera.injera-stock-levels.index');
        })->name('injera-stock-levels.index');
        Route::get('/cost-analysis', function () {
            return view('admin.injera.cost-analysis.index');
        })->name('cost-analysis.index');
        Route::get('/orders', function () {
            return view('admin.injera.orders.index');
        })->name('orders.index');
    });

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', function () {
            return view('admin.reports.sales');
        })->name('sales.index');
        Route::get('/customers', function () {
            return view('admin.reports.customers');
        })->name('customers.index');
        Route::get('/menu', function () {
            return view('admin.reports.menu');
        })->name('menu.index');
        Route::get('/inventory', function () {
            return view('admin.reports.inventory');
        })->name('inventory.index');
        Route::get('/staff', function () {
            return view('admin.reports.staff');
        })->name('staff.index');
        Route::get('/financial', function () {
            return view('admin.reports.financial');
        })->name('financial.index');
        Route::get('/operational', function () {
            return view('admin.reports.operational');
        })->name('operational.index');
        Route::get('/executive', function () {
            return view('admin.reports.executive');
        })->name('executive.index');
    });

    // To-Do Management Routes
    Route::prefix('todos')->name('todos.')->group(function () {
        Route::get('/overview', [\App\Http\Controllers\Admin\TodosController::class, 'overview'])->name('overview.index');
        Route::get('/progress', [\App\Http\Controllers\Admin\TodosController::class, 'progress'])->name('progress.index');
        Route::get('/schedules', [\App\Http\Controllers\Admin\TodosController::class, 'schedules'])->name('schedules.index');
        Route::get('/schedules/create', [\App\Http\Controllers\Admin\TodosController::class, 'createSchedule'])->name('schedules.create');
        Route::get('/staff-lists', [\App\Http\Controllers\Admin\TodosController::class, 'staffLists'])->name('staff-lists.index');
        Route::get('/staff-lists/{id}', [\App\Http\Controllers\Admin\TodosController::class, 'showStaffList'])->name('staff-lists.show');
        Route::get('/templates', [\App\Http\Controllers\Admin\TodosController::class, 'templates'])->name('templates.index');
        Route::get('/templates/create', [\App\Http\Controllers\Admin\TodosController::class, 'createTemplate'])->name('templates.create');
    });

    // Activity Tracking Routes
    Route::prefix('activities')->name('activities.')->group(function () {
        Route::get('/manage', [\App\Http\Controllers\Admin\ActivitiesController::class, 'manage'])->name('manage.index');
        Route::get('/manage/create', [\App\Http\Controllers\Admin\ActivitiesController::class, 'create'])->name('manage.create');
        Route::get('/manage/settings', [\App\Http\Controllers\Admin\ActivitiesController::class, 'settings'])->name('manage.settings');
        Route::get('/assignments', [\App\Http\Controllers\Admin\ActivitiesController::class, 'assignments'])->name('assignments.index');
        Route::get('/logging', [\App\Http\Controllers\Admin\ActivitiesController::class, 'logging'])->name('logging.index');
        Route::get('/analytics', [\App\Http\Controllers\Admin\ActivitiesController::class, 'analytics'])->name('analytics.index');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('admin.settings.index');
        })->name('index');

        // Department Management
        Route::resource('departments', \App\Http\Controllers\Admin\DepartmentController::class);
        Route::patch('departments/{department}/toggle-status', [\App\Http\Controllers\Admin\DepartmentController::class, 'toggleStatus'])->name('departments.toggle-status');

        // Shift Type Management
        Route::resource('shift-types', \App\Http\Controllers\Admin\ShiftTypeController::class);
        Route::patch('shift-types/{shiftType}/toggle-status', [\App\Http\Controllers\Admin\ShiftTypeController::class, 'toggleStatus'])->name('shift-types.toggle-status');
    });
});

// Language switching route (placeholder)
Route::get('/language/{locale}', function ($locale) {
    // For now, just redirect back - we'll implement language switching later
    return redirect()->back();
})->name('language.switch')->where('locale', 'en|am|ti');
