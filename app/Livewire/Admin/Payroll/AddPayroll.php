<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\Staff;
use App\Models\StaffPayrollPeriod;
use App\Models\StaffPayrollRecord;
use App\Models\StaffPayrollSetting;
use App\Models\StaffPayrollTemplate;
use App\Models\StaffAttendance;
use App\Services\PayrollCalculationService;
use Illuminate\Support\Collection;
use Livewire\Component;

class AddPayroll extends Component
{
    // Step 1: Period Selection
    public ?string $selectedPeriodId = null;
    
    // Step 2: Staff Selection
    public ?string $selectedStaffId = null;
    public string $staffSearchTerm = '';
    
    // Step 3: Attendance Selection
    public array $selectedAttendanceIds = [];
    public bool $selectAllAttendance = false;
    
    // Step 4: Manual Adjustments (optional)
    public ?float $manualHours = null;
    public string $notes = '';
    
    // Period creation modal
    public bool $showCreatePeriodModal = false;
    public string $newPeriodName = '';
    public string $newPeriodStart = '';
    public string $newPeriodEnd = '';
    public string $newPayDate = '';

    protected $queryString = [
        'staffSearchTerm' => ['except' => ''],
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

    public function selectStaff(string $staffId): void
    {
        $this->selectedStaffId = $staffId;
        $this->selectedAttendanceIds = [];
        $this->selectAllAttendance = false;
        $this->manualHours = null;
        $this->notes = '';
    }

    public function clearStaffSelection(): void
    {
        $this->selectedStaffId = null;
        $this->selectedAttendanceIds = [];
        $this->selectAllAttendance = false;
        $this->manualHours = null;
        $this->notes = '';
        $this->staffSearchTerm = '';
    }

    public function updatedSelectAllAttendance($value): void
    {
        if ($value) {
            $this->selectedAttendanceIds = $this->getAttendanceRecords()->pluck('id')->toArray();
        } else {
            $this->selectedAttendanceIds = [];
        }
    }

    public function toggleAttendanceSelection(string $attendanceId): void
    {
        if (in_array($attendanceId, $this->selectedAttendanceIds)) {
            $this->selectedAttendanceIds = array_values(array_diff($this->selectedAttendanceIds, [$attendanceId]));
        } else {
            $this->selectedAttendanceIds[] = $attendanceId;
        }
        
        $this->selectAllAttendance = false;
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
        if (!$this->selectedPeriodId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select a pay period.'
            ]);
            return;
        }

        if (!$this->selectedStaffId) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select a staff member.'
            ]);
            return;
        }

        if (empty($this->selectedAttendanceIds) && !$this->manualHours) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Please select attendance records or enter manual hours.'
            ]);
            return;
        }

        try {
            $period = StaffPayrollPeriod::findOrFail($this->selectedPeriodId);
            $staff = Staff::with(['profile', 'staffType'])->findOrFail($this->selectedStaffId);
            
            // Validate period status
            if ($period->status !== 'open') {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Payroll can only be generated for open periods.'
                ]);
                return;
            }

            // Check if payroll already exists (only non-deleted records)
            $existing = $period->payrollRecords()
                ->where('staff_id', $this->selectedStaffId)
                ->whereNull('deleted_at')
                ->exists();
            
            if ($existing) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => "{$staff->full_name} already has a payroll record for this period."
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

            // Calculate hours
            $totalHours = 0;
            if ($this->manualHours) {
                $totalHours = (float) $this->manualHours;
            } else {
                $attendance = StaffAttendance::whereIn('id', $this->selectedAttendanceIds)->get();
                $totalHours = $attendance->sum('net_hours_worked') ?? 0;
            }

            // Get hourly rate
            $hourlyRate = $staff->profile?->hourly_rate ?? 0;
            
            if ($hourlyRate <= 0) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => "{$staff->full_name} has no valid hourly rate."
                ]);
                return;
            }

            // Calculate pay components
            $regularHours = $setting->calculateRegularHours($totalHours);
            $overtimeHours = $setting->calculateOvertimeHours($totalHours);
            
            $regularPay = $regularHours * $hourlyRate;
            $overtimePay = $overtimeHours * $hourlyRate * $setting->overtime_multiplier;
            $grossPay = $regularPay + $overtimePay;
            
            // For now, no deductions
            $deductions = 0;
            $netPay = $grossPay - $deductions;

            // Create payroll record
            $record = StaffPayrollRecord::create([
                'pay_period_id' => $period->id,
                'staff_id' => $staff->id,
                'payroll_template_id' => $template->id,
                'pay_period_start' => $period->period_start,
                'pay_period_end' => $period->period_end,
                'regular_hours' => $regularHours,
                'overtime_hours' => $overtimeHours,
                'hourly_rate' => $hourlyRate,
                'regular_pay' => $regularPay,
                'overtime_pay' => $overtimePay,
                'gross_pay' => $grossPay,
                'deductions' => $deductions,
                'net_pay' => $netPay,
                'status' => 'calculated',
                'notes' => $this->notes ?: null,
                'created_by' => auth()->id(),
            ]);

            // Update period totals
            $period->refresh();
            $period->updateTotals();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Payroll generated for {$staff->full_name}! Gross Pay: £" . number_format($grossPay, 2)
            ]);

            // Clear selection
            $this->clearStaffSelection();
            
        } catch (\Exception $e) {
            \Log::error('Critical error in payroll generation', [
                'error' => $e->getMessage(),
                'period_id' => $this->selectedPeriodId,
                'staff_id' => $this->selectedStaffId,
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error generating payroll: ' . $e->getMessage()
            ]);
        }
    }

    public function getStaffList(): Collection
    {
        if (!$this->selectedPeriodId) {
            return collect([]);
        }

        $period = StaffPayrollPeriod::find($this->selectedPeriodId);
        if (!$period) {
            return collect([]);
        }

        $query = Staff::with(['profile', 'staffType'])
            ->whereHas('profile')
            ->where('status', 'active');

        if ($this->staffSearchTerm) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', '%' . $this->staffSearchTerm . '%')
                  ->orWhere('last_name', 'like', '%' . $this->staffSearchTerm . '%')
                  ->orWhere('email', 'like', '%' . $this->staffSearchTerm . '%');
            });
        }

        return $query->get()->map(function ($staff) use ($period) {
            $hasPayroll = $period->payrollRecords()
                ->where('staff_id', $staff->id)
                ->exists();

            return (object) [
                'id' => $staff->id,
                'name' => $staff->full_name,
                'employee_id' => $staff->profile?->employee_id,
                'staff_type' => $staff->staffType?->name,
                'hourly_rate' => $staff->profile?->hourly_rate ?? 0,
                'has_payroll' => $hasPayroll,
            ];
        });
    }

    public function getAttendanceRecords(): Collection
    {
        if (!$this->selectedStaffId || !$this->selectedPeriodId) {
            return collect([]);
        }

        $period = StaffPayrollPeriod::find($this->selectedPeriodId);
        if (!$period) {
            return collect([]);
        }

        return StaffAttendance::where('staff_id', $this->selectedStaffId)
            ->whereBetween('clock_in', [
                $period->period_start->startOfDay(),
                $period->period_end->endOfDay()
            ])
            ->whereNotNull('clock_out')
            ->orderBy('clock_in', 'asc')
            ->get();
    }

    public function getSelectedStaff()
    {
        if (!$this->selectedStaffId) {
            return null;
        }

        $staff = Staff::with(['profile', 'staffType'])->find($this->selectedStaffId);
        if (!$staff) {
            return null;
        }

        return (object) [
            'id' => $staff->id,
            'name' => $staff->full_name,
            'employee_id' => $staff->profile?->employee_id,
            'staff_type' => $staff->staffType?->name,
            'hourly_rate' => $staff->profile?->hourly_rate ?? 0,
        ];
    }

    public function getCalculatedTotals(): array
    {
        if ($this->manualHours) {
            $totalHours = (float) $this->manualHours;
        } else {
            $attendance = StaffAttendance::whereIn('id', $this->selectedAttendanceIds)->get();
            $totalHours = $attendance->sum('net_hours_worked') ?? 0;
        }

        $staff = $this->getSelectedStaff();
        $hourlyRate = $staff->hourly_rate ?? 0;
        $grossPay = $totalHours * $hourlyRate;

        return [
            'total_hours' => $totalHours,
            'hourly_rate' => $hourlyRate,
            'gross_pay' => $grossPay,
        ];
    }

    public function render()
    {
        $periods = StaffPayrollPeriod::where('status', 'open')
            ->orderBy('period_start', 'desc')
            ->get();

        $selectedPeriod = $this->selectedPeriodId 
            ? StaffPayrollPeriod::find($this->selectedPeriodId) 
            : null;

        $staffList = $this->getStaffList();
        $selectedStaff = $this->getSelectedStaff();
        $attendanceRecords = $this->getAttendanceRecords();
        $calculatedTotals = $this->getCalculatedTotals();

        return view('livewire.admin.payroll.add-payroll', [
            'periods' => $periods,
            'selectedPeriod' => $selectedPeriod,
            'staffList' => $staffList,
            'selectedStaff' => $selectedStaff,
            'attendanceRecords' => $attendanceRecords,
            'calculatedTotals' => $calculatedTotals,
        ]);
    }
}
