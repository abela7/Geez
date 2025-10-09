<?php

declare(strict_types=1);

namespace App\Services;

use App\Helpers\PayrollRoundingHelper;
use App\Models\Staff;
use App\Models\StaffAttendance;
use App\Models\StaffPayrollBonus;
use App\Models\StaffPayrollDeduction;
use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Models\StaffPayrollRecordDetail;
use App\Models\StaffPayrollSetting;
use App\Models\StaffPayrollTemplate;
use App\Models\SystemAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Enhanced payroll calculation service with full processing logic.
 */
class PayrollCalculationService
{
    public function __construct(
        protected PayrollValidationService $validator
    ) {
    }

    /**
     * Calculate payroll for a single staff member for a period.
     */
    public function calculateForStaff(
        Staff $staff,
        StaffPayrollPeriod $period,
        ?StaffPayrollTemplate $template = null
    ): StaffPayrollRecord {
        // Validate staff
        $errors = $this->validator->validateStaffForPayroll($staff);
        $this->validator->throwIfErrors($errors, 'Staff validation failed');

        return DB::transaction(function () use ($staff, $period, $template) {
            // Get or create template
            $template = $template ?? $this->getTemplateForStaff($staff);
            $setting = $period->payrollSetting ?? StaffPayrollSetting::getDefault();

            // Check for duplicate
            $existing = $this->validator->checkDuplicatePayroll(
                $staff->id,
                $period->id,
                $period->period_start->startOfDay(),
                $period->period_end->endOfDay()
            );

            if ($existing) {
                return $existing;
            }

            // Create payroll record
            $record = $this->createPayrollRecord($staff, $period, $template);

            // Calculate hours from attendance
            $hoursData = $this->calculateHoursFromAttendance(
                $staff,
                $period->period_start,
                $period->period_end,
                $setting
            );

            // Calculate pay
            $payData = $this->calculatePay($hoursData, $staff, $template);

            // Get bonuses
            $bonuses = $this->getBonusesForPeriod($staff, $period);
            $bonusTotal = $bonuses->sum('amount');

            // Calculate deductions
            $deductionsData = $this->calculateDeductions($staff, $payData['gross_pay'] + $bonusTotal);

            // Calculate final amounts
            $grossPay = PayrollRoundingHelper::sumAndRound([
                $payData['regular_pay'],
                $payData['overtime_pay'],
                $bonusTotal,
            ]);

            $totalDeductions = PayrollRoundingHelper::sumAndRound([
                $deductionsData['tax_deductions'],
                $deductionsData['other_deductions'],
            ]);

            $netPay = PayrollRoundingHelper::calculateNetPay($grossPay, $totalDeductions);

            // Update record
            $record->update([
                'regular_hours' => $hoursData['regular_hours'],
                'regular_pay' => $payData['regular_pay'],
                'overtime_hours' => $hoursData['overtime_hours'],
                'overtime_pay' => $payData['overtime_pay'],
                'bonus_total' => $bonusTotal,
                'gross_pay' => $grossPay,
                'tax_deductions' => $deductionsData['tax_deductions'],
                'other_deductions' => $deductionsData['other_deductions'],
                'deductions' => $totalDeductions,
                'net_pay' => $netPay,
                'status' => $netPay < 0 ? 'needs_review' : 'calculated',
            ]);

            // Create detail line items
            $this->createDetailLineItems($record, $hoursData, $payData, $bonuses, $deductionsData);

            // Link bonuses to this record
            $bonuses->each(fn ($bonus) => $bonus->update(['payroll_record_id' => $record->id]));

            // Audit log
            SystemAuditLog::logAction(
                'staff_payroll_records',
                $record->id,
                'create',
                null,
                $record->toArray(),
                "Payroll calculated for {$staff->full_name} - Period: {$period->name}",
                'low'
            );

            return $record->fresh();
        });
    }

    /**
     * Create initial payroll record with snapshots.
     */
    protected function createPayrollRecord(
        Staff $staff,
        StaffPayrollPeriod $period,
        StaffPayrollTemplate $template
    ): StaffPayrollRecord {
        $generationHash = hash('sha256', implode('|', [
            $staff->id,
            $period->id,
            $period->period_start->toDateTimeString(),
            $period->period_end->toDateTimeString(),
        ]));

        $record = StaffPayrollRecord::create([
            'staff_id' => $staff->id,
            'pay_period_id' => $period->id,
            'template_id' => $template->id,
            'pay_period_start' => $period->period_start,
            'pay_period_end' => $period->period_end,
            'generated_from' => 'attendance',
            'source_period_start' => $period->period_start->startOfDay(),
            'source_period_end' => $period->period_end->endOfDay(),
            'generation_hash' => $generationHash,
            'currency' => $template->currency ?? 'USD',
            'hourly_rate' => $staff->profile?->hourly_rate ?? $template->base_hourly_rate ?? 0,
            'overtime_rate' => $template->getOvertimeMultiplier(),
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        // Capture snapshot
        $record->captureStaffSnapshot();

        return $record;
    }

    /**
     * Calculate hours from attendance records.
     */
    protected function calculateHoursFromAttendance(
        Staff $staff,
        Carbon $periodStart,
        Carbon $periodEnd,
        ?StaffPayrollSetting $setting = null
    ): array {
        $setting = $setting ?? StaffPayrollSetting::getDefault();

        $attendances = StaffAttendance::where('staff_id', $staff->id)
            ->whereBetween('clock_in', [$periodStart, $periodEnd])
            ->where('current_state', 'clocked_out')
            ->get();

        $totalNetHours = $attendances->sum('net_hours_worked');

        $regularHours = $setting?->calculateRegularHours($totalNetHours) ?? min($totalNetHours, 40);
        $overtimeHours = $setting?->calculateOvertimeHours($totalNetHours) ?? max($totalNetHours - 40, 0);

        return [
            'total_hours' => PayrollRoundingHelper::roundCurrency($totalNetHours),
            'regular_hours' => PayrollRoundingHelper::roundCurrency($regularHours),
            'overtime_hours' => PayrollRoundingHelper::roundCurrency($overtimeHours),
            'attendance_count' => $attendances->count(),
        ];
    }

    /**
     * Calculate pay based on hours and rates.
     */
    protected function calculatePay(
        array $hoursData,
        Staff $staff,
        StaffPayrollTemplate $template
    ): array {
        $hourlyRate = $staff->profile?->hourly_rate ?? $template->base_hourly_rate ?? 0;
        $overtimeMultiplier = $template->getOvertimeMultiplier();

        $regularPay = PayrollRoundingHelper::roundCurrency(
            $hoursData['regular_hours'] * $hourlyRate
        );

        $overtimePay = PayrollRoundingHelper::roundCurrency(
            $hoursData['overtime_hours'] * $hourlyRate * $overtimeMultiplier
        );

        $grossPay = PayrollRoundingHelper::sumAndRound([$regularPay, $overtimePay]);

        return [
            'hourly_rate' => $hourlyRate,
            'overtime_multiplier' => $overtimeMultiplier,
            'regular_pay' => $regularPay,
            'overtime_pay' => $overtimePay,
            'gross_pay' => $grossPay,
        ];
    }

    /**
     * Get bonuses for the period.
     */
    protected function getBonusesForPeriod(
        Staff $staff,
        StaffPayrollPeriod $period
    ) {
        return StaffPayrollBonus::where('staff_id', $staff->id)
            ->where('pay_period_id', $period->id)
            ->where('status', 'approved')
            ->whereNull('payroll_record_id')
            ->get();
    }

    /**
     * Calculate all deductions.
     */
    protected function calculateDeductions(Staff $staff, float $grossPay): array
    {
        $today = now();

        // Get active deductions for this staff member
        $activeDeductions = StaffPayrollDeduction::where('staff_id', $staff->id)
            ->where('status', 'active')
            ->where('effective_from', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $today);
            })
            ->with('deductionType')
            ->get();

        $taxDeductions = 0;
        $otherDeductions = 0;

        foreach ($activeDeductions as $deduction) {
            $deductionType = $deduction->deductionType;
            
            if (! $deductionType) {
                continue;
            }

            // Calculate deduction amount
            $amount = $deduction->custom_amount ?? $deductionType->calculateAmount(
                $grossPay,
                $deduction->custom_rate
            );

            $amount = PayrollRoundingHelper::roundCurrency($amount);

            // Categorize deduction
            if (str_contains(strtolower($deductionType->name), 'tax')) {
                $taxDeductions += $amount;
            } else {
                $otherDeductions += $amount;
            }
        }

        return [
            'tax_deductions' => PayrollRoundingHelper::roundCurrency($taxDeductions),
            'other_deductions' => PayrollRoundingHelper::roundCurrency($otherDeductions),
            'total_deductions' => PayrollRoundingHelper::sumAndRound([$taxDeductions, $otherDeductions]),
            'deduction_count' => $activeDeductions->count(),
        ];
    }

    /**
     * Create detailed line items for the payroll record.
     */
    protected function createDetailLineItems(
        StaffPayrollRecord $record,
        array $hoursData,
        array $payData,
        $bonuses,
        array $deductionsData
    ): void {
        $sortOrder = 0;

        // Regular hours
        if ($hoursData['regular_hours'] > 0) {
            StaffPayrollRecordDetail::create([
                'payroll_record_id' => $record->id,
                'item_type' => 'regular_hours',
                'description' => 'Regular Hours',
                'quantity' => $hoursData['regular_hours'],
                'rate' => $payData['hourly_rate'],
                'amount' => $payData['regular_pay'],
                'currency' => $record->currency,
                'affects' => 'gross',
                'is_taxable' => true,
                'sort_order' => $sortOrder++,
                'created_by' => auth()->id(),
            ]);
        }

        // Overtime hours
        if ($hoursData['overtime_hours'] > 0) {
            StaffPayrollRecordDetail::create([
                'payroll_record_id' => $record->id,
                'item_type' => 'overtime_hours',
                'description' => 'Overtime Hours',
                'quantity' => $hoursData['overtime_hours'],
                'rate' => $payData['hourly_rate'] * $payData['overtime_multiplier'],
                'amount' => $payData['overtime_pay'],
                'currency' => $record->currency,
                'affects' => 'gross',
                'is_taxable' => true,
                'sort_order' => $sortOrder++,
                'created_by' => auth()->id(),
            ]);
        }

        // Bonuses
        foreach ($bonuses as $bonus) {
            StaffPayrollRecordDetail::create([
                'payroll_record_id' => $record->id,
                'item_type' => 'bonus',
                'description' => $bonus->name,
                'quantity' => 1,
                'rate' => $bonus->amount,
                'amount' => $bonus->amount,
                'currency' => $bonus->currency,
                'affects' => 'gross',
                'is_taxable' => $bonus->is_taxable,
                'source_type' => 'StaffPayrollBonus',
                'source_id' => $bonus->id,
                'sort_order' => $sortOrder++,
                'created_by' => auth()->id(),
            ]);
        }

        // Tax deductions
        if ($deductionsData['tax_deductions'] > 0) {
            StaffPayrollRecordDetail::create([
                'payroll_record_id' => $record->id,
                'item_type' => 'tax',
                'description' => 'Tax Deductions',
                'quantity' => 1,
                'rate' => $deductionsData['tax_deductions'],
                'amount' => -$deductionsData['tax_deductions'],
                'currency' => $record->currency,
                'affects' => 'net',
                'is_taxable' => false,
                'sort_order' => $sortOrder++,
                'created_by' => auth()->id(),
            ]);
        }

        // Other deductions
        if ($deductionsData['other_deductions'] > 0) {
            StaffPayrollRecordDetail::create([
                'payroll_record_id' => $record->id,
                'item_type' => 'deduction',
                'description' => 'Other Deductions',
                'quantity' => 1,
                'rate' => $deductionsData['other_deductions'],
                'amount' => -$deductionsData['other_deductions'],
                'currency' => $record->currency,
                'affects' => 'net',
                'is_taxable' => false,
                'sort_order' => $sortOrder++,
                'created_by' => auth()->id(),
            ]);
        }
    }

    /**
     * Get appropriate template for staff member.
     */
    protected function getTemplateForStaff(Staff $staff): StaffPayrollTemplate
    {
        // Try to find template for staff type, otherwise use default
        $template = StaffPayrollTemplate::active()->default()->first();

        if (! $template) {
            throw new \Exception('No active payroll template found');
        }

        return $template;
    }

    /**
     * Recalculate existing payroll record.
     */
    public function recalculate(StaffPayrollRecord $record): StaffPayrollRecord
    {
        if ($record->isFinalized()) {
            throw new \Exception('Cannot recalculate finalized payroll');
        }

        $staff = $record->staff;
        $period = $record->payPeriod;
        $template = $record->template ?? $this->getTemplateForStaff($staff);

        return DB::transaction(function () use ($record, $staff, $period, $template) {
            // Delete existing details
            $record->details()->delete();

            // Recalculate
            $setting = $period->payrollSetting ?? StaffPayrollSetting::getDefault();

            $hoursData = $this->calculateHoursFromAttendance(
                $staff,
                $period->period_start,
                $period->period_end,
                $setting
            );

            $payData = $this->calculatePay($hoursData, $staff, $template);
            $bonuses = $this->getBonusesForPeriod($staff, $period);
            $bonusTotal = $bonuses->sum('amount');
            $deductionsData = $this->calculateDeductions($staff, $payData['gross_pay'] + $bonusTotal);

            $grossPay = PayrollRoundingHelper::sumAndRound([
                $payData['regular_pay'],
                $payData['overtime_pay'],
                $bonusTotal,
            ]);

            $totalDeductions = PayrollRoundingHelper::sumAndRound([
                $deductionsData['tax_deductions'],
                $deductionsData['other_deductions'],
            ]);

            $netPay = PayrollRoundingHelper::calculateNetPay($grossPay, $totalDeductions);

            $record->update([
                'regular_hours' => $hoursData['regular_hours'],
                'regular_pay' => $payData['regular_pay'],
                'overtime_hours' => $hoursData['overtime_hours'],
                'overtime_pay' => $payData['overtime_pay'],
                'bonus_total' => $bonusTotal,
                'gross_pay' => $grossPay,
                'tax_deductions' => $deductionsData['tax_deductions'],
                'other_deductions' => $deductionsData['other_deductions'],
                'deductions' => $totalDeductions,
                'net_pay' => $netPay,
                'status' => $netPay < 0 ? 'needs_review' : 'calculated',
            ]);

            $this->createDetailLineItems($record, $hoursData, $payData, $bonuses, $deductionsData);

            SystemAuditLog::logAction(
                'staff_payroll_records',
                $record->id,
                'recalculate',
                null,
                $record->toArray(),
                "Payroll recalculated for {$staff->full_name}",
                'medium'
            );

            return $record->fresh();
        });
    }
}
