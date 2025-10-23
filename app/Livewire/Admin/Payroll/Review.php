<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Models\StaffPayrollSetting;
use App\Models\SystemAuditLog;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Review extends Component
{
    use WithPagination;

    public StaffPayrollPeriod $period;
    public string $search = '';
    public string $statusFilter = 'all';
    public array $selectedRecords = [];
    public bool $selectAll = false;
    
    // Recalculate modal
    public bool $showRecalculateModal = false;
    public ?StaffPayrollRecord $recalculatingRecord = null;
    
    // Approve confirmation modal
    public bool $showApproveModal = false;
    public string $approvalNotes = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function mount(StaffPayrollPeriod $period): void
    {
        $this->period = $period;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
        $this->selectedRecords = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedRecords = $this->getRecordsProperty()->pluck('id')->toArray();
        } else {
            $this->selectedRecords = [];
        }
    }

    public function toggleRecordSelection(string $recordId): void
    {
        if (in_array($recordId, $this->selectedRecords)) {
            $this->selectedRecords = array_values(array_diff($this->selectedRecords, [$recordId]));
        } else {
            $this->selectedRecords[] = $recordId;
        }
        $this->selectAll = false;
    }

    public function openRecalculateModal(string $recordId): void
    {
        $this->recalculatingRecord = StaffPayrollRecord::with('staff.profile')->findOrFail($recordId);
        $this->showRecalculateModal = true;
    }

    public function closeRecalculateModal(): void
    {
        $this->showRecalculateModal = false;
        $this->recalculatingRecord = null;
    }

    public function recalculateRecord(): void
    {
        if (!$this->recalculatingRecord) {
            return;
        }

        try {
            $record = $this->recalculatingRecord;
            $staff = $record->staff;
            $setting = StaffPayrollSetting::getDefault();

            if (!$setting) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Default payroll setting not found.'
                ]);
                return;
            }

            // Get current values
            $totalHours = $record->regular_hours + $record->overtime_hours;
            $hourlyRate = $staff->profile?->hourly_rate ?? $record->hourly_rate;

            // Recalculate
            $regularHours = $setting->calculateRegularHours($totalHours);
            $overtimeHours = $setting->calculateOvertimeHours($totalHours);
            
            $regularPay = $regularHours * $hourlyRate;
            $overtimePay = $overtimeHours * $hourlyRate * $setting->overtime_multiplier;
            $grossPay = $regularPay + $overtimePay;
            
            $netPay = $grossPay - ($record->deductions ?? 0);

            $oldValues = $record->only(['regular_hours', 'overtime_hours', 'hourly_rate', 'gross_pay', 'net_pay']);

            // Update record
            $record->update([
                'regular_hours' => $regularHours,
                'overtime_hours' => $overtimeHours,
                'hourly_rate' => $hourlyRate,
                'regular_pay' => $regularPay,
                'overtime_pay' => $overtimePay,
                'gross_pay' => $grossPay,
                'net_pay' => $netPay,
                'status' => 'calculated', // Reset to calculated after recalc
                'updated_by' => auth()->id(),
            ]);

            // Log audit trail
            SystemAuditLog::logAction(
                'staff_payroll_records',
                $record->id,
                'recalculate',
                $oldValues,
                $record->only(['regular_hours', 'overtime_hours', 'hourly_rate', 'gross_pay', 'net_pay']),
                "Payroll recalculated for {$staff->full_name}",
                'medium'
            );

            // Update period totals
            $this->period->updateTotals();
            $this->period->refresh();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Payroll recalculated for {$staff->full_name}!"
            ]);

            $this->closeRecalculateModal();
        } catch (\Exception $e) {
            \Log::error('Error recalculating payroll', [
                'record_id' => $this->recalculatingRecord->id,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error recalculating payroll: ' . $e->getMessage()
            ]);
        }
    }

    public function openApproveModal(): void
    {
        if (empty($this->selectedRecords)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select at least one payroll record to approve.'
            ]);
            return;
        }

        $this->showApproveModal = true;
        $this->approvalNotes = '';
    }

    public function closeApproveModal(): void
    {
        $this->showApproveModal = false;
        $this->approvalNotes = '';
    }

    public function approveSelected(): void
    {
        if (empty($this->selectedRecords)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No records selected for approval.'
            ]);
            return;
        }

        try {
            $records = StaffPayrollRecord::with('staff')
                ->whereIn('id', $this->selectedRecords)
                ->whereIn('status', ['calculated', 'needs_review'])
                ->get();

            if ($records->isEmpty()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No valid records to approve. Only calculated or needs_review records can be approved.'
                ]);
                return;
            }

            $approvedCount = 0;
            foreach ($records as $record) {
                $oldStatus = $record->status;
                
                $record->update([
                    'status' => 'approved',
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                    'notes' => $this->approvalNotes ? ($record->notes ? $record->notes . "\n\nApproval: " . $this->approvalNotes : "Approval: " . $this->approvalNotes) : $record->notes,
                    'updated_by' => auth()->id(),
                ]);

                // Log audit trail
                SystemAuditLog::logAction(
                    'staff_payroll_records',
                    $record->id,
                    'approve',
                    ['status' => $oldStatus],
                    ['status' => 'approved'],
                    "Payroll approved for {$record->staff->full_name}" . ($this->approvalNotes ? ": {$this->approvalNotes}" : ''),
                    'high'
                );

                $approvedCount++;
            }

            // Update period totals
            $this->period->updateTotals();
            $this->period->refresh();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "{$approvedCount} payroll record(s) approved successfully!"
            ]);

            $this->selectedRecords = [];
            $this->selectAll = false;
            $this->closeApproveModal();
        } catch (\Exception $e) {
            \Log::error('Error approving payroll records', [
                'record_ids' => $this->selectedRecords,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error approving records: ' . $e->getMessage()
            ]);
        }
    }

    public function rejectRecord(string $recordId): void
    {
        try {
            $record = StaffPayrollRecord::with('staff')->findOrFail($recordId);
            $oldStatus = $record->status;

            $record->update([
                'status' => 'needs_review',
                'updated_by' => auth()->id(),
            ]);

            // Log audit trail
            SystemAuditLog::logAction(
                'staff_payroll_records',
                $record->id,
                'reject',
                ['status' => $oldStatus],
                ['status' => 'needs_review'],
                "Payroll rejected for {$record->staff->full_name} - requires review",
                'medium'
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Payroll for {$record->staff->full_name} marked as needs review."
            ]);
        } catch (\Exception $e) {
            \Log::error('Error rejecting payroll record', [
                'record_id' => $recordId,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error rejecting record: ' . $e->getMessage()
            ]);
        }
    }

    public function recalculateAll(): void
    {
        try {
            $records = $this->period->payrollRecords()
                ->with('staff.profile')
                ->whereIn('status', ['calculated', 'needs_review'])
                ->get();

            if ($records->isEmpty()) {
                $this->dispatch('notify', [
                    'type' => 'info',
                    'message' => 'No records available to recalculate.'
                ]);
                return;
            }

            $setting = StaffPayrollSetting::getDefault();
            if (!$setting) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Default payroll setting not found.'
                ]);
                return;
            }

            $recalculatedCount = 0;
            foreach ($records as $record) {
                $staff = $record->staff;
                $totalHours = $record->regular_hours + $record->overtime_hours;
                $hourlyRate = $staff->profile?->hourly_rate ?? $record->hourly_rate;

                $regularHours = $setting->calculateRegularHours($totalHours);
                $overtimeHours = $setting->calculateOvertimeHours($totalHours);
                
                $regularPay = $regularHours * $hourlyRate;
                $overtimePay = $overtimeHours * $hourlyRate * $setting->overtime_multiplier;
                $grossPay = $regularPay + $overtimePay;
                
                $netPay = $grossPay - ($record->deductions ?? 0);

                $record->update([
                    'regular_hours' => $regularHours,
                    'overtime_hours' => $overtimeHours,
                    'hourly_rate' => $hourlyRate,
                    'regular_pay' => $regularPay,
                    'overtime_pay' => $overtimePay,
                    'gross_pay' => $grossPay,
                    'net_pay' => $netPay,
                    'updated_by' => auth()->id(),
                ]);

                // Log audit trail
                SystemAuditLog::logAction(
                    'staff_payroll_records',
                    $record->id,
                    'bulk_recalculate',
                    null,
                    null,
                    "Payroll recalculated (bulk) for {$staff->full_name}",
                    'medium'
                );

                $recalculatedCount++;
            }

            // Update period totals
            $this->period->updateTotals();
            $this->period->refresh();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "{$recalculatedCount} payroll record(s) recalculated successfully!"
            ]);
        } catch (\Exception $e) {
            \Log::error('Error recalculating all payroll records', [
                'period_id' => $this->period->id,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error recalculating records: ' . $e->getMessage()
            ]);
        }
    }

    public function getRecordsProperty(): Collection
    {
        $query = $this->period->payrollRecords()->with(['staff.profile', 'staff.staffType']);

        if ($this->search) {
            $query->whereHas('staff', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getStatusStatsProperty(): array
    {
        $records = $this->period->payrollRecords;
        
        return [
            'draft' => $records->where('status', 'draft')->count(),
            'calculated' => $records->where('status', 'calculated')->count(),
            'needs_review' => $records->where('status', 'needs_review')->count(),
            'approved' => $records->where('status', 'approved')->count(),
            'paid' => $records->where('status', 'paid')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.payroll.review', [
            'records' => $this->records,
            'statusStats' => $this->statusStats,
        ]);
    }
}

