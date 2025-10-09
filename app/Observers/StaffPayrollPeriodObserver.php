<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\StaffPayrollPeriod;
use App\Models\SystemAuditLog;

class StaffPayrollPeriodObserver
{
    /**
     * Handle the StaffPayrollPeriod "created" event.
     */
    public function created(StaffPayrollPeriod $period): void
    {
        SystemAuditLog::logAction(
            'staff_payroll_periods',
            $period->id,
            'create',
            null,
            $period->toArray(),
            "Payroll period created: {$period->name}",
            'medium'
        );
    }

    /**
     * Handle the StaffPayrollPeriod "updated" event.
     */
    public function updated(StaffPayrollPeriod $period): void
    {
        if ($period->isDirty('status')) {
            $oldStatus = $period->getOriginal('status');
            $newStatus = $period->status;

            SystemAuditLog::logAction(
                'staff_payroll_periods',
                $period->id,
                'update',
                ['status' => $oldStatus],
                ['status' => $newStatus],
                "Payroll period {$period->name} status changed from {$oldStatus} to {$newStatus}",
                $newStatus === 'closed' ? 'high' : 'medium'
            );
        }
    }

    /**
     * Handle the StaffPayrollPeriod "deleted" event.
     */
    public function deleted(StaffPayrollPeriod $period): void
    {
        SystemAuditLog::logAction(
            'staff_payroll_periods',
            $period->id,
            'delete',
            $period->toArray(),
            null,
            "Payroll period deleted: {$period->name}",
            'high'
        );
    }
}

