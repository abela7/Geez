<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Models\StaffPayrollPaymentMethod;
use App\Models\SystemAuditLog;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Payment extends Component
{
    use WithPagination;

    public StaffPayrollPeriod $period;
    public string $search = '';
    public array $selectedRecords = [];
    public bool $selectAll = false;
    
    // Payment details
    public string $paymentMethod = 'bank_transfer';
    public string $paymentDate = '';
    public string $transactionReference = '';
    public string $bankName = '';
    public string $paymentNotes = '';
    
    // Process payment modal
    public bool $showProcessModal = false;
    
    // Batch ID for tracking
    public ?string $currentBatchId = null;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'paymentMethod' => 'required|in:cash,bank_transfer,check,mobile_money,other',
        'paymentDate' => 'required|date',
        'transactionReference' => 'nullable|string|max:100',
        'bankName' => 'nullable|string|max:100',
        'paymentNotes' => 'nullable|string|max:500',
    ];

    public function mount(StaffPayrollPeriod $period): void
    {
        $this->period = $period;
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedRecords = $this->getApprovedRecordsProperty()->pluck('id')->toArray();
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

    public function openProcessModal(): void
    {
        if (empty($this->selectedRecords)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select at least one payroll record to process payment.'
            ]);
            return;
        }

        $this->showProcessModal = true;
        $this->resetValidation();
    }

    public function closeProcessModal(): void
    {
        $this->showProcessModal = false;
    }

    public function processPayment(): void
    {
        $this->validate();

        if (empty($this->selectedRecords)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No records selected for payment processing.'
            ]);
            return;
        }

        try {
            $records = StaffPayrollRecord::with('staff')
                ->whereIn('id', $this->selectedRecords)
                ->where('status', 'approved')
                ->get();

            if ($records->isEmpty()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No valid records to process. Only approved records can be paid.'
                ]);
                return;
            }

            // Generate batch ID for tracking
            $this->currentBatchId = 'BATCH-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));
            
            $processedCount = 0;
            $sequence = 1;

            foreach ($records as $record) {
                // Create payment method record
                $payment = StaffPayrollPaymentMethod::create([
                    'payroll_record_id' => $record->id,
                    'payment_method' => $this->paymentMethod,
                    'amount_paid' => $record->net_pay,
                    'currency' => 'GBP',
                    'payment_date' => $this->paymentDate,
                    'transaction_reference' => $this->transactionReference ?: null,
                    'bank_name' => $this->bankName ?: null,
                    'status' => 'processed',
                    'processed_at' => now(),
                    'batch_id' => $this->currentBatchId,
                    'batch_sequence' => $sequence++,
                    'notes' => $this->paymentNotes ?: null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                // Update payroll record status to 'paid'
                $oldStatus = $record->status;
                $record->update([
                    'status' => 'paid',
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                    'updated_by' => auth()->id(),
                ]);

                // Log audit trail
                SystemAuditLog::logAction(
                    'staff_payroll_records',
                    $record->id,
                    'payment_processed',
                    ['status' => $oldStatus],
                    ['status' => 'paid'],
                    "Payment processed for {$record->staff->full_name} via {$this->paymentMethod} (Batch: {$this->currentBatchId})",
                    'critical'
                );

                // Log payment creation
                SystemAuditLog::logAction(
                    'staff_payroll_payment_methods',
                    $payment->id,
                    'create',
                    null,
                    $payment->only(['payment_method', 'amount_paid', 'payment_date', 'batch_id']),
                    "Payment method created for {$record->staff->full_name}: £{$record->net_pay} via {$this->paymentMethod}",
                    'high'
                );

                $processedCount++;
            }

            // Update period totals
            $this->period->updateTotals();
            $this->period->refresh();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "{$processedCount} payment(s) processed successfully! Batch ID: {$this->currentBatchId}"
            ]);

            $this->selectedRecords = [];
            $this->selectAll = false;
            $this->resetForm();
            $this->closeProcessModal();
        } catch (\Exception $e) {
            \Log::error('Error processing payments', [
                'record_ids' => $this->selectedRecords,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error processing payments: ' . $e->getMessage()
            ]);
        }
    }

    public function markAsPaid(string $recordId): void
    {
        try {
            $record = StaffPayrollRecord::with('staff')->findOrFail($recordId);

            if ($record->status !== 'approved') {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Only approved records can be marked as paid.'
                ]);
                return;
            }

            // Create a simple payment record
            $payment = StaffPayrollPaymentMethod::create([
                'payroll_record_id' => $record->id,
                'payment_method' => 'other',
                'amount_paid' => $record->net_pay,
                'currency' => 'GBP',
                'payment_date' => now()->format('Y-m-d'),
                'status' => 'processed',
                'processed_at' => now(),
                'notes' => 'Manually marked as paid',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            // Update record status
            $record->update([
                'status' => 'paid',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
                'updated_by' => auth()->id(),
            ]);

            // Log audit trail
            SystemAuditLog::logAction(
                'staff_payroll_records',
                $record->id,
                'mark_as_paid',
                ['status' => 'approved'],
                ['status' => 'paid'],
                "Manually marked as paid for {$record->staff->full_name}",
                'high'
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Payment marked as processed for {$record->staff->full_name}!"
            ]);
        } catch (\Exception $e) {
            \Log::error('Error marking as paid', [
                'record_id' => $recordId,
                'error' => $e->getMessage()
            ]);
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error marking as paid: ' . $e->getMessage()
            ]);
        }
    }

    public function resetForm(): void
    {
        $this->paymentMethod = 'bank_transfer';
        $this->paymentDate = now()->format('Y-m-d');
        $this->transactionReference = '';
        $this->bankName = '';
        $this->paymentNotes = '';
        $this->resetValidation();
    }

    public function getApprovedRecordsProperty(): Collection
    {
        $query = $this->period->payrollRecords()
            ->with(['staff.profile', 'staff.staffType'])
            ->where('status', 'approved');

        if ($this->search) {
            $query->whereHas('staff', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function getTotalSelectedAmountProperty(): float
    {
        if (empty($this->selectedRecords)) {
            return 0.0;
        }

        return StaffPayrollRecord::whereIn('id', $this->selectedRecords)
            ->sum('net_pay') ?? 0.0;
    }

    public function render()
    {
        return view('livewire.admin.payroll.payment', [
            'approvedRecords' => $this->approvedRecords,
            'totalSelectedAmount' => $this->totalSelectedAmount,
        ]);
    }
}

