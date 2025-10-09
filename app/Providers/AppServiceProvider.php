<?php

namespace App\Providers;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Observers\StaffPayrollPeriodObserver;
use App\Observers\StaffPayrollRecordObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register payroll observers for audit logging
        StaffPayrollRecord::observe(StaffPayrollRecordObserver::class);
        StaffPayrollPeriod::observe(StaffPayrollPeriodObserver::class);
    }
}
