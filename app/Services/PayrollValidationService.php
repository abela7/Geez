<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Validates payroll data before processing.
 */
class PayrollValidationService
{
    /**
     * Validate that a payroll period can be used for generation.
     */
    public function validatePeriodForGeneration(StaffPayrollPeriod $period): array
    {
        $errors = [];

        if ($period->isClosed()) {
            $errors[] = 'Cannot generate payroll for closed period';
        }

        if ($period->isProcessing()) {
            $errors[] = 'Period is currently being processed';
        }

        if ($period->period_start->isFuture()) {
            $errors[] = 'Cannot generate payroll for future periods';
        }

        return $errors;
    }

    /**
     * Validate staff member can have payroll generated.
     */
    public function validateStaffForPayroll(Staff $staff): array
    {
        $errors = [];

        if (! $staff->isActive()) {
            $errors[] = "Staff member {$staff->full_name} is not active";
        }

        if (! $staff->profile) {
            $errors[] = "Staff member {$staff->full_name} has no profile";
        }

        if ($staff->profile && ! $staff->profile->hourly_rate) {
            $errors[] = "Staff member {$staff->full_name} has no hourly rate set";
        }

        return $errors;
    }

    /**
     * Validate payroll record before approval.
     */
    public function validateRecordForApproval(StaffPayrollRecord $record): array
    {
        $errors = [];

        if (! $record->isCalculated()) {
            $errors[] = 'Record must be in calculated status to approve';
        }

        if ($record->net_pay < 0) {
            $errors[] = 'Cannot approve record with negative net pay';
        }

        if (! $record->regular_hours && ! $record->overtime_hours && ! $record->bonus_total) {
            $errors[] = 'Record has no hours or bonuses';
        }

        return $errors;
    }

    /**
     * Validate payroll record before marking as paid.
     */
    public function validateRecordForPayment(StaffPayrollRecord $record): array
    {
        $errors = [];

        if (! $record->isApproved()) {
            $errors[] = 'Record must be approved before payment';
        }

        if ($record->net_pay <= 0) {
            $errors[] = 'Cannot pay record with zero or negative amount';
        }

        return $errors;
    }

    /**
     * Check for duplicate payroll generation.
     */
    public function checkDuplicatePayroll(
        string $staffId,
        string $payPeriodId,
        Carbon $sourceStart,
        Carbon $sourceEnd
    ): ?StaffPayrollRecord {
        $hash = hash('sha256', implode('|', [
            $staffId,
            $payPeriodId,
            $sourceStart->toDateTimeString(),
            $sourceEnd->toDateTimeString(),
        ]));

        return StaffPayrollRecord::where('staff_id', $staffId)
            ->where('pay_period_id', $payPeriodId)
            ->where('generation_hash', $hash)
            ->first();
    }

    /**
     * Validate that period dates don't overlap with existing open periods.
     */
    public function validatePeriodOverlap(
        string $periodType,
        Carbon $periodStart,
        Carbon $periodEnd,
        ?string $excludePeriodId = null
    ): array {
        $query = StaffPayrollPeriod::where('period_type', $periodType)
            ->where('status', 'open')
            ->where(function ($q) use ($periodStart, $periodEnd) {
                $q->whereBetween('period_start', [$periodStart, $periodEnd])
                    ->orWhereBetween('period_end', [$periodStart, $periodEnd])
                    ->orWhere(function ($q2) use ($periodStart, $periodEnd) {
                        $q2->where('period_start', '<=', $periodStart)
                            ->where('period_end', '>=', $periodEnd);
                    });
            });

        if ($excludePeriodId) {
            $query->where('id', '!=', $excludePeriodId);
        }

        $overlapping = $query->get();

        if ($overlapping->isEmpty()) {
            return [];
        }

        return [
            'Period overlaps with existing open period(s): ' .
            $overlapping->pluck('name')->implode(', '),
        ];
    }

    /**
     * Validate amounts are within reasonable ranges.
     */
    public function validateAmounts(array $data): array
    {
        $errors = [];

        if (isset($data['gross_pay']) && $data['gross_pay'] > 1000000) {
            $errors[] = 'Gross pay exceeds maximum allowed amount';
        }

        if (isset($data['deductions']) && $data['deductions'] < 0) {
            $errors[] = 'Deductions cannot be negative';
        }

        if (isset($data['regular_hours']) && $data['regular_hours'] > 744) {
            $errors[] = 'Regular hours exceed maximum hours in a month';
        }

        if (isset($data['overtime_hours']) && $data['overtime_hours'] > 372) {
            $errors[] = 'Overtime hours exceed reasonable maximum';
        }

        return $errors;
    }

    /**
     * Throw validation exception if errors exist.
     */
    public function throwIfErrors(array $errors, string $context = 'Validation failed'): void
    {
        if (! empty($errors)) {
            throw ValidationException::withMessages([
                'validation' => $errors,
            ]);
        }
    }
}

