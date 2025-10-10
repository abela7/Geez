<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollSetting;
use App\Models\Staff;
use App\Services\PayrollGenerationService;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;
use Carbon\Carbon;

class Periods extends Component
{
    use WithPagination;

    // Form properties
    #[Rule('required|string|max:255')]
    public string $name = '';
    
    #[Rule('required|in:weekly,biweekly,monthly')]
    public string $period_type = 'monthly';
    
    #[Rule('required|date')]
    public string $period_start = '';
    
    #[Rule('required|date|after:period_start')]
    public string $period_end = '';
    
    #[Rule('required|date|after_or_equal:period_end')]
    public string $pay_date = '';
    
    #[Rule('nullable|exists:staff_payroll_settings,id')]
    public ?string $payroll_setting_id = null;
    
    #[Rule('nullable|string|max:500')]
    public string $notes = '';

    // UI state
    public bool $showCreateForm = false;
    public bool $isEditing = false;
    public ?StaffPayrollPeriod $currentPeriod = null;
    
    // Filters
    public string $statusFilter = 'all';
    public string $search = '';

    public function mount(): void
    {
        $this->resetForm();
    }

    public function render()
    {
        $query = StaffPayrollPeriod::with(['payrollSetting', 'creator'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->orderBy('period_start', 'desc');

        return view('livewire.admin.payroll.periods', [
            'periods' => $query->paginate(10),
            'payrollSettings' => StaffPayrollSetting::active()->get(),
            'statusCounts' => $this->getStatusCounts(),
        ]);
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->showCreateForm = true;
        $this->isEditing = false;
        
        // Set default dates for current month
        $now = Carbon::now();
        $this->period_start = $now->startOfMonth()->format('Y-m-d');
        $this->period_end = $now->endOfMonth()->format('Y-m-d');
        $this->pay_date = $now->endOfMonth()->addDays(5)->format('Y-m-d');
        $this->name = $now->format('F Y') . ' Payroll';
        
        // Set default payroll setting
        $defaultSetting = StaffPayrollSetting::getDefault();
        $this->payroll_setting_id = $defaultSetting?->id;
    }

    public function startEdit(string $periodId): void
    {
        $period = StaffPayrollPeriod::findOrFail($periodId);
        
        // Allow editing for all periods
        $this->currentPeriod = $period;
        $this->fillFromPeriod($period);
        $this->showCreateForm = true;
        $this->isEditing = true;
    }

    public function save(): void
    {
        $this->validate();

        try {
            // Check for overlapping periods
            $overlapping = StaffPayrollPeriod::where('id', '!=', $this->currentPeriod?->id ?? '')
                ->where(function ($query) {
                    $query->whereBetween('period_start', [$this->period_start, $this->period_end])
                        ->orWhereBetween('period_end', [$this->period_start, $this->period_end])
                        ->orWhere(function ($q) {
                            $q->where('period_start', '<=', $this->period_start)
                              ->where('period_end', '>=', $this->period_end);
                        });
                })
                ->exists();

            if ($overlapping) {
                $this->addError('period_start', 'This period overlaps with an existing period.');
                return;
            }

            $data = [
                'name' => $this->name,
                'period_type' => $this->period_type,
                'period_start' => $this->period_start,
                'period_end' => $this->period_end,
                'pay_date' => $this->pay_date,
                'payroll_setting_id' => $this->payroll_setting_id,
                'notes' => $this->notes,
            ];

            if ($this->isEditing && $this->currentPeriod) {
                $this->currentPeriod->update([
                    ...$data,
                    'updated_by' => auth()->id(),
                ]);

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Pay period updated successfully!'
                ]);
            } else {
                $period = StaffPayrollPeriod::create([
                    ...$data,
                    'status' => 'open',
                    'created_by' => auth()->id(),
                ]);

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Pay period created successfully!'
                ]);
            }

            $this->cancelForm();
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error saving period: ' . $e->getMessage()
            ]);
        }
    }

    public function generatePayroll(string $periodId): void
    {
        try {
            $period = StaffPayrollPeriod::findOrFail($periodId);
            
            if ($period->status !== 'draft') {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Payroll can only be generated for draft periods.'
                ]);
                return;
            }

            // Get active staff members
            $activeStaff = Staff::active()->get();
            
            if ($activeStaff->isEmpty()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No active staff members found.'
                ]);
                return;
            }

            // Use the PayrollGenerationService
            $service = new PayrollGenerationService();
            $result = $service->generateForPeriod($period, $activeStaff->pluck('id')->toArray());

            $period->update([
                'status' => 'calculated',
                'total_records' => $result['total_records'],
                'total_gross_pay' => $result['total_gross'],
                'total_net_pay' => $result['total_net'],
                'updated_by' => auth()->id(),
            ]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Payroll generated successfully! {$result['total_records']} records created."
            ]);

            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error generating payroll: ' . $e->getMessage()
            ]);
        }
    }

    public function deletePeriod(string $periodId): void
    {
        try {
            $period = StaffPayrollPeriod::findOrFail($periodId);
            
            // Allow deletion for all periods
            $period->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Pay period deleted successfully!'
            ]);

            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error deleting period: ' . $e->getMessage()
            ]);
        }
    }

    public function setStatusFilter(string $status): void
    {
        $this->statusFilter = $status;
        $this->resetPage();
    }

    public function cancelForm(): void
    {
        $this->showCreateForm = false;
        $this->isEditing = false;
        $this->resetForm();
        $this->resetValidation();
    }

    private function fillFromPeriod(StaffPayrollPeriod $period): void
    {
        $this->name = $period->name;
        $this->period_type = $period->period_type;
        $this->period_start = $period->period_start->format('Y-m-d');
        $this->period_end = $period->period_end->format('Y-m-d');
        $this->pay_date = $period->pay_date ? $period->pay_date->format('Y-m-d') : '';
        $this->payroll_setting_id = $period->payroll_setting_id;
        $this->notes = $period->notes ?? '';
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->period_type = 'monthly';
        $this->period_start = '';
        $this->period_end = '';
        $this->pay_date = '';
        $this->payroll_setting_id = null;
        $this->notes = '';
        $this->currentPeriod = null;
    }

    private function getStatusCounts(): array
    {
        return [
            'all' => StaffPayrollPeriod::count(),
            'draft' => StaffPayrollPeriod::where('status', 'draft')->count(),
            'calculated' => StaffPayrollPeriod::where('status', 'calculated')->count(),
            'approved' => StaffPayrollPeriod::where('status', 'approved')->count(),
            'paid' => StaffPayrollPeriod::where('status', 'paid')->count(),
        ];
    }
}
