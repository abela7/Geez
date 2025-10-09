<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Payroll;

use App\Models\StaffPayrollSetting;
use App\Models\StaffPayrollTemplate;
use App\Models\StaffPayrollTaxBracket;
use App\Models\StaffPayrollDeductionType;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Settings extends Component
{
    public string $activeTab = 'general';
    
    // General Settings
    #[Rule('required|string|max:255')]
    public string $name = '';
    
    #[Rule('required|in:weekly,biweekly,monthly')]
    public string $pay_frequency = 'monthly';
    
    #[Rule('required|numeric|min:1|max:168')]
    public float $overtime_threshold_hours = 40.00;
    
    #[Rule('required|numeric|min:1|max:5')]
    public float $overtime_multiplier = 1.50;
    
    #[Rule('required|string|size:3')]
    public string $currency_code = 'USD';
    
    #[Rule('required|in:up,down,nearest')]
    public string $rounding_mode = 'nearest';
    
    #[Rule('required|boolean')]
    public bool $auto_calculate_tax = true;
    
    // Current setting being edited
    public ?StaffPayrollSetting $currentSetting = null;
    
    // UI State
    public bool $showCreateForm = false;
    public bool $isEditing = false;

    public function mount(): void
    {
        $this->loadDefaultSetting();
    }

    public function render()
    {
        return view('livewire.admin.payroll.settings', [
            'settings' => StaffPayrollSetting::orderBy('is_default', 'desc')->get(),
            'templates' => StaffPayrollTemplate::active()->orderBy('sort_order')->get(),
            'taxBrackets' => StaffPayrollTaxBracket::active()->orderBy('tax_year', 'desc')->get(),
            'deductionTypes' => StaffPayrollDeductionType::active()->ordered()->get(),
        ]);
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    public function loadDefaultSetting(): void
    {
        $setting = StaffPayrollSetting::getDefault();
        
        if ($setting) {
            $this->currentSetting = $setting;
            $this->fillFromSetting($setting);
        } else {
            $this->resetForm();
        }
    }

    public function fillFromSetting(StaffPayrollSetting $setting): void
    {
        $this->name = $setting->name;
        $this->pay_frequency = $setting->pay_frequency;
        $this->overtime_threshold_hours = $setting->overtime_threshold_hours;
        $this->overtime_multiplier = $setting->overtime_multiplier;
        $this->currency_code = $setting->currency_code;
        $this->rounding_mode = $setting->rounding_mode;
        $this->auto_calculate_tax = $setting->auto_calculate_tax;
    }

    public function startCreate(): void
    {
        $this->resetForm();
        $this->showCreateForm = true;
        $this->isEditing = false;
    }

    public function startEdit(string $settingId): void
    {
        $setting = StaffPayrollSetting::findOrFail($settingId);
        $this->currentSetting = $setting;
        $this->fillFromSetting($setting);
        $this->showCreateForm = true;
        $this->isEditing = true;
    }

    public function save(): void
    {
        $this->validate();

        try {
            if ($this->isEditing && $this->currentSetting) {
                // Update existing
                $this->currentSetting->update([
                    'name' => $this->name,
                    'pay_frequency' => $this->pay_frequency,
                    'overtime_threshold_hours' => $this->overtime_threshold_hours,
                    'overtime_multiplier' => $this->overtime_multiplier,
                    'currency_code' => $this->currency_code,
                    'rounding_mode' => $this->rounding_mode,
                    'auto_calculate_tax' => $this->auto_calculate_tax,
                    'updated_by' => auth()->id(),
                ]);

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Payroll settings updated successfully!'
                ]);
            } else {
                // Create new
                $setting = StaffPayrollSetting::create([
                    'name' => $this->name,
                    'pay_frequency' => $this->pay_frequency,
                    'overtime_threshold_hours' => $this->overtime_threshold_hours,
                    'overtime_multiplier' => $this->overtime_multiplier,
                    'currency_code' => $this->currency_code,
                    'rounding_mode' => $this->rounding_mode,
                    'auto_calculate_tax' => $this->auto_calculate_tax,
                    'is_active' => true,
                    'is_default' => StaffPayrollSetting::count() === 0, // First one is default
                    'created_by' => auth()->id(),
                ]);

                $this->currentSetting = $setting;

                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'Payroll settings created successfully!'
                ]);
            }

            $this->cancelForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error saving settings: ' . $e->getMessage()
            ]);
        }
    }

    public function cancelForm(): void
    {
        $this->showCreateForm = false;
        $this->isEditing = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function makeDefault(string $settingId): void
    {
        try {
            // Remove default from all settings
            StaffPayrollSetting::query()->update(['is_default' => false]);
            
            // Set new default
            $setting = StaffPayrollSetting::findOrFail($settingId);
            $setting->update(['is_default' => true]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "'{$setting->name}' is now the default payroll setting."
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error updating default setting: ' . $e->getMessage()
            ]);
        }
    }

    public function toggleActive(string $settingId): void
    {
        try {
            $setting = StaffPayrollSetting::findOrFail($settingId);
            
            if ($setting->is_default && $setting->is_active) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'Cannot deactivate the default setting.'
                ]);
                return;
            }

            $setting->update(['is_active' => !$setting->is_active]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $setting->is_active ? 'Setting activated.' : 'Setting deactivated.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error updating setting: ' . $e->getMessage()
            ]);
        }
    }

    private function resetForm(): void
    {
        $this->name = 'Default Payroll Settings';
        $this->pay_frequency = 'monthly';
        $this->overtime_threshold_hours = 40.00;
        $this->overtime_multiplier = 1.50;
        $this->currency_code = 'USD';
        $this->rounding_mode = 'nearest';
        $this->auto_calculate_tax = true;
        $this->currentSetting = null;
    }
}
