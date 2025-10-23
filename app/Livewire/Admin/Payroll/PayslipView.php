<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollRecord;
use Livewire\Component;

class PayslipView extends Component
{
    public StaffPayrollRecord $record;

    public function mount(StaffPayrollRecord $record): void
    {
        // Load relationships
        $this->record = $record->load([
            'staff.profile',
            'staff.staffType',
            'payPeriod',
        ]);
    }

    public function downloadPayslip(): void
    {
        // TODO: Implement PDF generation
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => 'PDF generation will be implemented soon.'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.payroll.payslip-view')
            ->layout('layouts.admin');
    }
}
