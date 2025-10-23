<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use Livewire\Component;
use Livewire\WithPagination;

class Records extends Component
{
    use WithPagination;

    public StaffPayrollPeriod $period;
    public string $search = '';
    public string $statusFilter = 'all';
    
    // Edit modal state
    public bool $showEditModal = false;
    public ?StaffPayrollRecord $editingRecord = null;
    public ?float $editRegularHours = null;
    public ?float $editOvertimeHours = null;
    public ?float $editHourlyRate = null;
    public ?float $editGrossPay = null;
    public ?float $editDeductions = null;
    public ?float $editNetPay = null;
    public string $editNotes = '';
    
    // Delete modal state
    public bool $showDeleteModal = false;
    public ?StaffPayrollRecord $recordToDelete = null;
    
    // Detail view modal state
    public bool $showDetailModal = false;
    public ?StaffPayrollRecord $viewingRecord = null;

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
    }

    public function openEditModal(string $recordId): void
    {
        $this->editingRecord = StaffPayrollRecord::with('staff')->findOrFail($recordId);
        
        $this->editRegularHours = (float) $this->editingRecord->regular_hours;
        $this->editOvertimeHours = (float) $this->editingRecord->overtime_hours;
        $this->editHourlyRate = (float) $this->editingRecord->hourly_rate;
        $this->editGrossPay = (float) $this->editingRecord->gross_pay;
        $this->editDeductions = (float) $this->editingRecord->deductions;
        $this->editNetPay = (float) $this->editingRecord->net_pay;
        $this->editNotes = $this->editingRecord->notes ?? '';
        
        $this->showEditModal = true;
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingRecord = null;
        $this->reset([
            'editRegularHours',
            'editOvertimeHours',
            'editHourlyRate',
            'editGrossPay',
            'editDeductions',
            'editNetPay',
            'editNotes'
        ]);
    }

    public function recalculateAmounts(): void
    {
        if (!$this->editingRecord) {
            return;
        }

        $regularPay = ($this->editRegularHours ?? 0) * ($this->editHourlyRate ?? 0);
        $overtimePay = ($this->editOvertimeHours ?? 0) * ($this->editHourlyRate ?? 0) * 1.5;
        
        $this->editGrossPay = $regularPay + $overtimePay;
        $this->editNetPay = $this->editGrossPay - ($this->editDeductions ?? 0);
    }

    public function saveRecord(): void
    {
        if (!$this->editingRecord) {
            return;
        }

        $this->validate([
            'editRegularHours' => 'required|numeric|min:0',
            'editOvertimeHours' => 'required|numeric|min:0',
            'editHourlyRate' => 'required|numeric|min:0',
            'editGrossPay' => 'required|numeric|min:0',
            'editDeductions' => 'required|numeric|min:0',
            'editNetPay' => 'required|numeric|min:0',
            'editNotes' => 'nullable|string|max:500',
        ]);

        $this->editingRecord->update([
            'regular_hours' => $this->editRegularHours,
            'overtime_hours' => $this->editOvertimeHours,
            'hourly_rate' => $this->editHourlyRate,
            'gross_pay' => $this->editGrossPay,
            'deductions' => $this->editDeductions,
            'net_pay' => $this->editNetPay,
            'notes' => $this->editNotes,
            'updated_by' => auth()->id(),
        ]);

        // Update period totals
        $this->period->updateTotals();
        $this->period->refresh();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Payroll record updated successfully!'
        ]);

        $this->closeEditModal();
    }

    public function confirmDelete(string $recordId): void
    {
        $this->recordToDelete = StaffPayrollRecord::findOrFail($recordId);
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->recordToDelete = null;
    }

    public function deleteRecord(): void
    {
        if (!$this->recordToDelete) {
            return;
        }

        $staffName = $this->recordToDelete->staff->full_name ?? 'Unknown';

        $this->recordToDelete->delete();

        // Update period totals
        $this->period->updateTotals();
        $this->period->refresh();

        // Reset pagination to show the first page after deletion
        $this->resetPage();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => "Payroll record for {$staffName} deleted successfully!"
        ]);

        $this->cancelDelete();
    }

    public function viewDetail(string $recordId): void
    {
        $this->viewingRecord = StaffPayrollRecord::with('staff', 'payPeriod')->findOrFail($recordId);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->viewingRecord = null;
    }

    public function getRecordsProperty()
    {
        $query = StaffPayrollRecord::with(['staff', 'payPeriod'])
            ->where('pay_period_id', $this->period->id);

        // Apply search filter
        if ($this->search) {
            $query->whereHas('staff', function ($q) {
                $q->where('first_name', 'like', '%' . $this->search . '%')
                  ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function render()
    {
        return view('livewire.admin.payroll.records', [
            'records' => $this->records,
        ]);
    }
}

