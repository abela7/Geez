<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\Staff;
use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollSetting;
use App\Models\StaffPayrollTemplate;
use App\Models\StaffAttendance;
use App\Services\PayrollCalculationService;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class AddPayroll extends Component
{
    use WithPagination;

    public ?string $selectedPeriodId = null;
    public string $searchTerm = '';
    public array $selectedStaff = [];
    public bool $selectAll = false;
    
    // Period creation modal
    public bool $showCreatePeriodModal = false;
    public string $newPeriodName = '';
    public string $newPeriodStart = '';
    public string $newPeriodEnd = '';
    public string $newPayDate = '';

    protected $queryString = [
        'searchTerm' => ['except' => ''],
    ];

    public function mount(): void
    {
        // Auto-select the most recent open period
        $openPeriod = StaffPayrollPeriod::where('status', 'open')
            ->latest('period_start')
            ->first();
        
        if ($openPeriod) {
            $this->selectedPeriodId = $openPeriod->id;
        }
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selectedStaff = $this->getStaffForPeriod()->pluck('id')->toArray();
        } else {
            $this->selectedStaff = [];
        }
    }

    public function toggleStaffSelection(string $staffId): void
    {
        if (in_array($staffId, $this->selectedStaff)) {
            $this->selectedStaff = array_values(array_diff($this->selectedStaff, [$staffId]));
        } else {
            $this->selectedStaff[] = $staffId;
        }
        
        $this->selectAll = false;
    }

    public function openCreatePeriodModal(): void
    {
        $this->showCreatePeriodModal = true;
        $this->newPeriodName = '';
        $this->newPeriodStart = '';
        $this->newPeriodEnd = '';
        $this->newPayDate = '';
    }

    public function closeCreatePeriodModal(): void
    {
        $this->showCreatePeriodModal = false;
    }

    public function createPeriod(): void
    {
        $this->validate([
            'newPeriodName' => 'required|string|max:255',
            'newPeriodStart' => 'required|date',
            'newPeriodEnd' => 'required|date|after:newPeriodStart',
            'newPayDate' => 'required|date|after_or_equal:newPeriodEnd',
        ]);

        $period = StaffPayrollPeriod::create([
            'name' => $this->newPeriodName,
            'period_type' => 'weekly',
            'period_start' => $this->newPeriodStart,
            'period_end' => $this->newPeriodEnd,
            'pay_date' => $this->newPayDate,
            'status' => 'open',
            'created_by' => auth()->id(),
        ]);

        $this->selectedPeriodId = $period->id;
        $this->closeCreatePeriodModal();
        
        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Pay period created successfully!'
        ]);
    }

    public function generatePayroll(): void
    {
        // Validate inputs
        if (empty($this->selectedStaff)) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select at least one staff member.'
            ]);
            return;
        }

        if (!$this->selectedPeriodId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select a pay period.'
            ]);
            return;
        }

        try {
            $period = StaffPayrollPeriod::findOrFail($this->selectedPeriodId);
            
            // Validate period status
            if ($period->status !== 'open') {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Payroll can only be generated for open periods.'
                ]);
                return;
            }

            // Verify payroll setting exists
            $setting = StaffPayrollSetting::getDefault();
            if (!$setting) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Default payroll setting not found. Please configure payroll settings first.'
                ]);
                return;
            }

            // Verify payroll template exists
            $template = StaffPayrollTemplate::where('is_default', true)->first();
            if (!$template) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Default payroll template not found. Please configure payroll template first.'
                ]);
                return;
            }

            // Use service layer for calculations
            $calculator = app(PayrollCalculationService::class);
            $successCount = 0;
            $failedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($this->selectedStaff as $staffId) {
                try {
                    $staff = Staff::with(['profile', 'staffType'])->findOrFail($staffId);
                    
                    // Validation: Check if staff has profile
                    if (!$staff->profile) {
                        $errors[] = "{$staff->full_name}: Missing staff profile.";
                        $skippedCount++;
                        continue;
                    }

                    // Validation: Check if staff has hourly rate
                    if (!$staff->profile->hourly_rate || $staff->profile->hourly_rate <= 0) {
                        $errors[] = "{$staff->full_name}: Missing or invalid hourly rate.";
                        $skippedCount++;
                        continue;
                    }
                    
                    // Validation: Check if payroll already exists
                    $existing = $period->payrollRecords()
                        ->where('staff_id', $staffId)
                        ->exists();
                    
                    if ($existing) {
                        $errors[] = "{$staff->full_name}: Already has payroll for this period.";
                        $skippedCount++;
                        continue;
                    }
                    
                    // Generate payroll using service
                    $calculator->calculateForStaff($staff, $period);
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $failedCount++;
                    $staffName = isset($staff) ? $staff->full_name : "Staff ID: {$staffId}";
                    $errors[] = "{$staffName}: " . $e->getMessage();
                    \Log::error("Payroll generation failed for staff {$staffId}", [
                        'error' => $e->getMessage(),
                        'period' => $period->id,
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Update period totals from database
            $period->refresh();
            $period->updateTotals();

            // Build response message
            $message = "Payroll generation complete. Success: {$successCount}";
            if ($skippedCount > 0) {
                $message .= ", Skipped: {$skippedCount}";
            }
            if ($failedCount > 0) {
                $message .= ", Failed: {$failedCount}";
            }

            // Log detailed errors if any
            if (!empty($errors)) {
                \Log::warning('Payroll generation had issues', [
                    'period' => $period->id,
                    'success' => $successCount,
                    'failed' => $failedCount,
                    'skipped' => $skippedCount,
                    'errors' => $errors
                ]);
            }

            $this->dispatch('notify', [
                'type' => ($failedCount > 0 || $skippedCount > 0) ? 'warning' : 'success',
                'message' => $message
            ]);

            // Clear selection and refresh
            $this->selectedStaff = [];
            $this->selectAll = false;
            $this->resetPage();
            
        } catch (\Exception $e) {
            \Log::error('Critical error in payroll generation', [
                'error' => $e->getMessage(),
                'period_id' => $this->selectedPeriodId,
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Critical error generating payroll. Please contact system administrator.'
            ]);
        }
    }

    public function getStaffForPeriod(): Collection
    {
        if (!$this->selectedPeriodId) {
            return collect([]);
        }

        $period = StaffPayrollPeriod::find($this->selectedPeriodId);
        if (!$period) {
            return collect([]);
        }

        $query = Staff::active()
            ->with(['profile', 'attendance' => function ($query) use ($period) {
                $query->whereBetween('clock_in', [
                    $period->period_start->startOfDay(),
                    $period->period_end->endOfDay()
                ])
                ->where('current_state', 'clocked_out');
            }]);

        if ($this->searchTerm) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->searchTerm . '%');
            });
        }

        return $query->get()->map(function ($staff) use ($period) {
            // Get attendance hours from database
            $totalHours = $staff->attendance->sum('net_hours_worked') ?? 0;
            
            // Get hourly rate from staff profile
            $hourlyRate = 0;
            if ($staff->profile && $staff->profile->hourly_rate) {
                $hourlyRate = (float) $staff->profile->hourly_rate;
            }
            
            // Calculate gross pay
            $grossPay = $totalHours * $hourlyRate;
            
            // Check if payroll already exists for this staff and period
            $hasPayroll = $period->payrollRecords()
                ->where('staff_id', $staff->id)
                ->exists();

            // Get staff type name
            $staffTypeName = $staff->staffType ? $staff->staffType->name : null;
            
            // Get employee ID
            $employeeId = $staff->profile ? $staff->profile->employee_id : null;
            
            return (object) [
                'id' => $staff->id,
                'name' => $staff->full_name,
                'employee_id' => $employeeId,
                'staff_type' => $staffTypeName,
                'hourly_rate' => $hourlyRate,
                'total_hours' => $totalHours,
                'gross_pay' => $grossPay,
                'has_payroll' => $hasPayroll,
                'has_profile' => $staff->profile !== null,
                'has_hourly_rate' => $hourlyRate > 0,
            ];
        });
    }

    public function render()
    {
        $periods = StaffPayrollPeriod::where('status', 'open')
            ->orderBy('period_start', 'desc')
            ->get();

        $selectedPeriod = $this->selectedPeriodId 
            ? StaffPayrollPeriod::find($this->selectedPeriodId)
            : null;

        $staffList = $this->getStaffForPeriod();

        return view('livewire.admin.payroll.add-payroll', [
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'staffList' => $staffList,
        ]);
    }
}

