<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    public StaffPayrollPeriod $period;
    public string $reportType = 'summary';
    public string $statusFilter = 'all';
    public string $search = '';
    
    protected $queryString = [
        'reportType' => ['except' => 'summary'],
        'statusFilter' => ['except' => 'all'],
        'search' => ['except' => ''],
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

    public function viewPayslip(string $recordId): void
    {
        $this->redirect(route('admin.staff.payroll.payslip', ['record' => $recordId]));
    }

    public function downloadPayslip(string $recordId): void
    {
        // TODO: Implement PDF generation
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'PDF generation will be implemented soon.'
        ]);
    }

    public function exportSummary(): void
    {
        // TODO: Implement CSV/Excel export
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'Export functionality will be implemented soon.'
        ]);
    }

    public function getRecordsProperty()
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

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getSummaryStatsProperty(): array
    {
        $records = $this->period->payrollRecords;
        
        return [
            'total_staff' => $records->count(),
            'total_gross_pay' => $records->sum('gross_pay'),
            'total_deductions' => $records->sum('deductions'),
            'total_net_pay' => $records->sum('net_pay'),
            'total_hours' => $records->sum('regular_hours') + $records->sum('overtime_hours'),
            'total_regular_hours' => $records->sum('regular_hours'),
            'total_overtime_hours' => $records->sum('overtime_hours'),
            'average_hourly_rate' => $records->avg('hourly_rate') ?? 0,
        ];
    }

    public function render()
    {
        return view('livewire.admin.payroll.reports', [
            'records' => $this->records,
            'summaryStats' => $this->summaryStats,
        ]);
    }
}

