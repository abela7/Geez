<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Staff;
use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Models\SystemAuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Handles batch payroll generation for periods.
 */
class PayrollGenerationService
{
    public function __construct(
        protected PayrollCalculationService $calculator,
        protected PayrollValidationService $validator
    ) {
    }

    /**
     * Generate payroll for all staff in a period.
     */
    public function generateForPeriod(
        StaffPayrollPeriod $period,
        ?array $staffIds = null
    ): array {
        // Validate period
        $errors = $this->validator->validatePeriodForGeneration($period);
        $this->validator->throwIfErrors($errors, 'Period validation failed');

        return DB::transaction(function () use ($period, $staffIds) {
            // Mark period as processing
            $period->update(['status' => 'processing']);

            try {
                // Get staff to process
                $staff = $this->getStaffForPeriod($period, $staffIds);

                $results = [
                    'success' => [],
                    'failed' => [],
                    'skipped' => [],
                    'total' => $staff->count(),
                ];

                foreach ($staff as $staffMember) {
                    try {
                        // Validate staff
                        $staffErrors = $this->validator->validateStaffForPayroll($staffMember);
                        
                        if (! empty($staffErrors)) {
                            $results['skipped'][] = [
                                'staff_id' => $staffMember->id,
                                'staff_name' => $staffMember->full_name,
                                'reasons' => $staffErrors,
                            ];
                            continue;
                        }

                        // Calculate payroll
                        $record = $this->calculator->calculateForStaff($staffMember, $period);

                        $results['success'][] = [
                            'staff_id' => $staffMember->id,
                            'staff_name' => $staffMember->full_name,
                            'record_id' => $record->id,
                            'net_pay' => $record->net_pay,
                        ];
                    } catch (\Exception $e) {
                        $results['failed'][] = [
                            'staff_id' => $staffMember->id,
                            'staff_name' => $staffMember->full_name,
                            'error' => $e->getMessage(),
                        ];
                    }
                }

                // Update period totals
                $period->updateTotals();

                // Mark period as processing (payroll calculated, ready for review/approval)
                $period->update(['status' => 'processing']);

                // Audit log
                SystemAuditLog::logAction(
                    'staff_payroll_periods',
                    $period->id,
                    'create',
                    null,
                    $results,
                    "Batch payroll generated for period {$period->name} - Success: " . count($results['success']) . ", Failed: " . count($results['failed']) . ", Skipped: " . count($results['skipped']),
                    'high'
                );

                return $results;
            } catch (\Exception $e) {
                // Mark period as open if generation fails
                $period->update(['status' => 'open']);
                throw $e;
            }
        });
    }

    /**
     * Get staff members for period generation.
     */
    protected function getStaffForPeriod(
        StaffPayrollPeriod $period,
        ?array $staffIds = null
    ): Collection {
        $query = Staff::active()->with(['profile', 'staffType']);

        if ($staffIds) {
            $query->whereIn('id', $staffIds);
        }

        return $query->get();
    }

    /**
     * Approve multiple payroll records.
     */
    public function approveRecords(array $recordIds, ?string $approvedBy = null): array
    {
        $results = [
            'approved' => [],
            'failed' => [],
        ];

        DB::transaction(function () use ($recordIds, $approvedBy, &$results) {
            $records = StaffPayrollRecord::whereIn('id', $recordIds)->get();

            foreach ($records as $record) {
                try {
                    $errors = $this->validator->validateRecordForApproval($record);
                    
                    if (! empty($errors)) {
                        $results['failed'][] = [
                            'record_id' => $record->id,
                            'staff_name' => $record->staff_name_snapshot,
                            'errors' => $errors,
                        ];
                        continue;
                    }

                    $record->markAsApproved($approvedBy);

                    $results['approved'][] = [
                        'record_id' => $record->id,
                        'staff_name' => $record->staff_name_snapshot,
                        'net_pay' => $record->net_pay,
                    ];

                    SystemAuditLog::logAction(
                        'staff_payroll_records',
                        $record->id,
                        'approve',
                        ['status' => 'calculated'],
                        ['status' => 'approved'],
                        "Payroll approved for {$record->staff_name_snapshot}",
                        'medium'
                    );
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'record_id' => $record->id,
                        'staff_name' => $record->staff_name_snapshot,
                        'errors' => [$e->getMessage()],
                    ];
                }
            }
        });

        return $results;
    }

    /**
     * Mark multiple records as paid.
     */
    public function markRecordsAsPaid(array $recordIds, ?string $processedBy = null): array
    {
        $results = [
            'paid' => [],
            'failed' => [],
        ];

        DB::transaction(function () use ($recordIds, $processedBy, &$results) {
            $records = StaffPayrollRecord::whereIn('id', $recordIds)->get();

            foreach ($records as $record) {
                try {
                    $errors = $this->validator->validateRecordForPayment($record);
                    
                    if (! empty($errors)) {
                        $results['failed'][] = [
                            'record_id' => $record->id,
                            'staff_name' => $record->staff_name_snapshot,
                            'errors' => $errors,
                        ];
                        continue;
                    }

                    $record->markAsPaid($processedBy);

                    $results['paid'][] = [
                        'record_id' => $record->id,
                        'staff_name' => $record->staff_name_snapshot,
                        'net_pay' => $record->net_pay,
                    ];

                    SystemAuditLog::logAction(
                        'staff_payroll_records',
                        $record->id,
                        'pay',
                        ['status' => 'approved'],
                        ['status' => 'paid'],
                        "Payroll paid for {$record->staff_name_snapshot}",
                        'high'
                    );
                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'record_id' => $record->id,
                        'staff_name' => $record->staff_name_snapshot,
                        'errors' => [$e->getMessage()],
                    ];
                }
            }
        });

        return $results;
    }

    /**
     * Recalculate multiple payroll records.
     */
    public function recalculateRecords(array $recordIds): array
    {
        $results = [
            'recalculated' => [],
            'failed' => [],
        ];

        foreach ($recordIds as $recordId) {
            try {
                $record = StaffPayrollRecord::findOrFail($recordId);
                
                $this->calculator->recalculate($record);

                $results['recalculated'][] = [
                    'record_id' => $record->id,
                    'staff_name' => $record->staff_name_snapshot,
                    'net_pay' => $record->net_pay,
                ];
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'record_id' => $recordId,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Get generation summary for a period.
     */
    public function getGenerationSummary(StaffPayrollPeriod $period): array
    {
        $records = $period->payrollRecords;

        return [
            'period_name' => $period->name,
            'period_start' => $period->period_start->toDateString(),
            'period_end' => $period->period_end->toDateString(),
            'total_records' => $records->count(),
            'by_status' => [
                'draft' => $records->where('status', 'draft')->count(),
                'calculated' => $records->where('status', 'calculated')->count(),
                'approved' => $records->where('status', 'approved')->count(),
                'paid' => $records->where('status', 'paid')->count(),
                'needs_review' => $records->where('status', 'needs_review')->count(),
            ],
            'totals' => [
                'gross_pay' => $records->sum('gross_pay'),
                'deductions' => $records->sum('deductions'),
                'net_pay' => $records->sum('net_pay'),
            ],
        ];
    }
}

