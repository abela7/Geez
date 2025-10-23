<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffPayrollPeriod;

class PayrollController extends Controller
{
    /**
     * Show payroll settings page.
     */
    public function settings()
    {
        return view('admin.payroll.settings');
    }

    /**
     * Show payroll periods list.
     */
    public function periods()
    {
        return view('admin.payroll.periods');
    }

    /**
     * Show add payroll page (generate payroll for individual staff).
     */
    public function add()
    {
        return view('admin.payroll.add');
    }

    /**
     * Show payroll records for a specific period.
     */
    public function records(StaffPayrollPeriod $period)
    {
        return view('admin.payroll.records', compact('period'));
    }

    /**
     * Show payroll review page for a period.
     */
    public function review(StaffPayrollPeriod $period)
    {
        return view('admin.payroll.review', compact('period'));
    }

    /**
     * Show payment processing page for a period.
     */
    public function payment(StaffPayrollPeriod $period)
    {
        return view('admin.payroll.payment', compact('period'));
    }
}