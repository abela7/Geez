<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\StaffPayrollRecord;
use App\Models\SystemAuditLog;

class StaffPayrollRecordObserver
{
    /**
     * Handle the StaffPayrollRecord "created" event.
     */
    public function created(StaffPayrollRecord $record): void
    {
        SystemAuditLog::logAction(
            'staff_payroll_records',
            $record->id,
            'create',
            null,
            $record->toArray(),
            "Payroll record created for {$record->staff_name_snapshot}",
            'medium'
        );
    }

    /**
     * Handle the StaffPayrollRecord "updated" event.
     */
    public function updated(StaffPayrollRecord $record): void
    {
        // Only log if significant fields changed
        $significantFields = [
            'status',
            'gross_pay',
            'deductions',
            'net_pay',
            'regular_hours',
            'overtime_hours',
        ];

        $changed = array_intersect($significantFields, array_keys($record->getDirty()));

        if (! empty($changed)) {
            SystemAuditLog::logAction(
                'staff_payroll_records',
                $record->id,
                'update',
                $record->getOriginal(),
                $record->toArray(),
                "Payroll record updated for {$record->staff_name_snapshot} - Changed: " . implode(', ', $changed),
                $record->status === 'approved' || $record->status === 'paid' ? 'high' : 'medium'
            );
        }
    }

    /**
     * Handle the StaffPayrollRecord "deleted" event.
     */
    public function deleted(StaffPayrollRecord $record): void
    {
        SystemAuditLog::logAction(
            'staff_payroll_records',
            $record->id,
            'delete',
            $record->toArray(),
            null,
            "Payroll record deleted for {$record->staff_name_snapshot}",
            'high'
        );
    }
}

