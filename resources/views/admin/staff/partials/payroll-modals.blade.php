<!-- Payroll Processing Modal -->
<div x-show="showPayrollModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-overlay"
     @click="closePayrollModal()">
    
    <div class="modal-container modal-container--large" @click.stop>
        <div class="modal-header">
            <h2 class="modal-title">{{ __('staff.payroll.process_payroll') }}</h2>
            <button @click="closePayrollModal()" class="modal-close" aria-label="{{ __('staff.payroll.close_modal') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form @submit.prevent="processPayroll()" class="modal-body">
            <div class="payroll-form-grid">
                <div class="form-group">
                    <label for="payroll-period" class="form-label">{{ __('staff.payroll.payroll_period') }} <span class="required">*</span></label>
                    <select id="payroll-period" x-model="payrollForm.period" class="form-select" required>
                        <option value="">{{ __('staff.payroll.select_period') }}</option>
                        <option value="current_month">{{ __('staff.payroll.current_month') }}</option>
                        <option value="previous_month">{{ __('staff.payroll.previous_month') }}</option>
                        <option value="custom">{{ __('staff.payroll.custom_period') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payroll-type" class="form-label">{{ __('staff.payroll.payroll_type') }}</label>
                    <select id="payroll-type" x-model="payrollForm.type" class="form-select">
                        <option value="regular">{{ __('staff.payroll.regular_payroll') }}</option>
                        <option value="bonus">{{ __('staff.payroll.bonus_payroll') }}</option>
                        <option value="overtime">{{ __('staff.payroll.overtime_payroll') }}</option>
                        <option value="adjustment">{{ __('staff.payroll.adjustment') }}</option>
                    </select>
                </div>

                <!-- Custom Period Dates -->
                <div x-show="payrollForm.period === 'custom'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-32"
                     class="form-group form-group--full custom-period">
                    <div class="date-range-grid">
                        <div class="form-group">
                            <label for="period-start" class="form-label">{{ __('staff.payroll.period_start') }}</label>
                            <input id="period-start" 
                                   type="date" 
                                   x-model="payrollForm.periodStart" 
                                   class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="period-end" class="form-label">{{ __('staff.payroll.period_end') }}</label>
                            <input id="period-end" 
                                   type="date" 
                                   x-model="payrollForm.periodEnd" 
                                   class="form-input">
                        </div>
                    </div>
                </div>

                <div class="form-group form-group--full">
                    <label class="form-label">{{ __('staff.payroll.select_employees') }}</label>
                    <div class="employee-selection">
                        <div class="selection-controls">
                            <button type="button" @click="selectAllEmployees()" class="selection-btn">
                                {{ __('staff.payroll.select_all') }}
                            </button>
                            <button type="button" @click="selectNoneEmployees()" class="selection-btn">
                                {{ __('staff.payroll.select_none') }}
                            </button>
                            <button type="button" @click="selectByDepartment()" class="selection-btn">
                                {{ __('staff.payroll.select_by_department') }}
                            </button>
                        </div>
                        
                        <div class="employee-list">
                            <template x-for="employee in employees" :key="employee.id">
                                <label class="employee-checkbox">
                                    <input type="checkbox" 
                                           :value="employee.id"
                                           x-model="payrollForm.selectedEmployees"
                                           class="form-checkbox">
                                    <div class="employee-info">
                                        <img :src="employee.avatar" :alt="employee.name" class="employee-avatar-tiny">
                                        <div class="employee-details">
                                            <span class="employee-name" x-text="employee.name"></span>
                                            <span class="employee-salary" x-text="formatCurrency(employee.base_salary)"></span>
                                        </div>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="form-group form-group--full">
                    <label for="payroll-notes" class="form-label">{{ __('staff.payroll.notes') }}</label>
                    <textarea id="payroll-notes" 
                              x-model="payrollForm.notes" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="{{ __('staff.payroll.notes_placeholder') }}"></textarea>
                </div>
            </div>

            <!-- Payroll Summary -->
            <div class="payroll-summary">
                <h3 class="summary-title">{{ __('staff.payroll.payroll_summary') }}</h3>
                <div class="summary-grid">
                    <div class="summary-item">
                        <span class="summary-label">{{ __('staff.payroll.employees_selected') }}</span>
                        <span class="summary-value" x-text="payrollForm.selectedEmployees.length"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ __('staff.payroll.total_gross') }}</span>
                        <span class="summary-value" x-text="formatCurrency(calculateTotalGross())"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">{{ __('staff.payroll.total_deductions') }}</span>
                        <span class="summary-value" x-text="formatCurrency(calculateTotalDeductions())"></span>
                    </div>
                    <div class="summary-item summary-item--total">
                        <span class="summary-label">{{ __('staff.payroll.total_net') }}</span>
                        <span class="summary-value" x-text="formatCurrency(calculateTotalNet())"></span>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="closePayrollModal()" class="btn btn-secondary">
                    {{ __('staff.payroll.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" :disabled="payrollForm.selectedEmployees.length === 0">
                    {{ __('staff.payroll.process_payroll') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Employee Payroll Edit Modal -->
<div x-show="showEmployeeModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-overlay"
     @click="closeEmployeeModal()">
    
    <div class="modal-container" @click.stop>
        <div class="modal-header">
            <h2 class="modal-title" x-text="editingEmployee ? `{{ __('staff.payroll.edit_employee_payroll') }} - ${editingEmployee.name}` : '{{ __('staff.payroll.employee_payroll') }}'"></h2>
            <button @click="closeEmployeeModal()" class="modal-close" aria-label="{{ __('staff.payroll.close_modal') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form @submit.prevent="saveEmployeePayroll()" class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="base-salary" class="form-label">{{ __('staff.payroll.base_salary') }} <span class="required">*</span></label>
                    <input id="base-salary" 
                           type="number" 
                           x-model="employeeForm.baseSalary" 
                           class="form-input"
                           step="0.01"
                           min="0"
                           required>
                </div>

                <div class="form-group">
                    <label for="hourly-rate" class="form-label">{{ __('staff.payroll.hourly_rate') }}</label>
                    <input id="hourly-rate" 
                           type="number" 
                           x-model="employeeForm.hourlyRate" 
                           class="form-input"
                           step="0.01"
                           min="0">
                </div>

                <div class="form-group">
                    <label for="overtime-rate" class="form-label">{{ __('staff.payroll.overtime_rate') }}</label>
                    <input id="overtime-rate" 
                           type="number" 
                           x-model="employeeForm.overtimeRate" 
                           class="form-input"
                           step="0.01"
                           min="0">
                </div>

                <div class="form-group">
                    <label for="payment-frequency" class="form-label">{{ __('staff.payroll.payment_frequency') }}</label>
                    <select id="payment-frequency" x-model="employeeForm.paymentFrequency" class="form-select">
                        <option value="monthly">{{ __('staff.payroll.monthly') }}</option>
                        <option value="bi-weekly">{{ __('staff.payroll.bi_weekly') }}</option>
                        <option value="weekly">{{ __('staff.payroll.weekly') }}</option>
                    </select>
                </div>

                <!-- Bonuses Section -->
                <div class="form-group form-group--full">
                    <label class="form-label">{{ __('staff.payroll.bonuses') }}</label>
                    <div class="adjustments-list">
                        <template x-for="(bonus, index) in employeeForm.bonuses" :key="index">
                            <div class="adjustment-item">
                                <input type="text" 
                                       x-model="bonus.description" 
                                       class="adjustment-description" 
                                       placeholder="{{ __('staff.payroll.bonus_description') }}">
                                <input type="number" 
                                       x-model="bonus.amount" 
                                       class="adjustment-amount" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                                <button type="button" @click="removeBonus(index)" class="adjustment-remove">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="addBonus()" class="adjustment-add">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('staff.payroll.add_bonus') }}
                        </button>
                    </div>
                </div>

                <!-- Deductions Section -->
                <div class="form-group form-group--full">
                    <label class="form-label">{{ __('staff.payroll.deductions') }}</label>
                    <div class="adjustments-list">
                        <template x-for="(deduction, index) in employeeForm.deductions" :key="index">
                            <div class="adjustment-item">
                                <input type="text" 
                                       x-model="deduction.description" 
                                       class="adjustment-description" 
                                       placeholder="{{ __('staff.payroll.deduction_description') }}">
                                <input type="number" 
                                       x-model="deduction.amount" 
                                       class="adjustment-amount" 
                                       step="0.01"
                                       min="0"
                                       placeholder="0.00">
                                <button type="button" @click="removeDeduction(index)" class="adjustment-remove">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="addDeduction()" class="adjustment-add">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            {{ __('staff.payroll.add_deduction') }}
                        </button>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="form-group form-group--full">
                    <h4 class="form-section-title">{{ __('staff.payroll.bank_details') }}</h4>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="bank-name" class="form-label">{{ __('staff.payroll.bank_name') }}</label>
                            <input id="bank-name" 
                                   type="text" 
                                   x-model="employeeForm.bankName" 
                                   class="form-input"
                                   placeholder="{{ __('staff.payroll.bank_name_placeholder') }}">
                        </div>
                        <div class="form-group">
                            <label for="account-number" class="form-label">{{ __('staff.payroll.account_number') }}</label>
                            <input id="account-number" 
                                   type="text" 
                                   x-model="employeeForm.accountNumber" 
                                   class="form-input"
                                   placeholder="{{ __('staff.payroll.account_number_placeholder') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="closeEmployeeModal()" class="btn btn-secondary">
                    {{ __('staff.payroll.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    {{ __('staff.payroll.save_changes') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Bonus Management Modal -->
<div x-show="showBonusModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-overlay"
     @click="closeBonusModal()">
    
    <div class="modal-container" @click.stop>
        <div class="modal-header">
            <h2 class="modal-title">{{ __('staff.payroll.manage_bonuses') }}</h2>
            <button @click="closeBonusModal()" class="modal-close" aria-label="{{ __('staff.payroll.close_modal') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form @submit.prevent="processBonuses()" class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="bonus-type" class="form-label">{{ __('staff.payroll.bonus_type') }}</label>
                    <select id="bonus-type" x-model="bonusForm.type" class="form-select">
                        <option value="performance">{{ __('staff.payroll.performance_bonus') }}</option>
                        <option value="holiday">{{ __('staff.payroll.holiday_bonus') }}</option>
                        <option value="attendance">{{ __('staff.payroll.attendance_bonus') }}</option>
                        <option value="custom">{{ __('staff.payroll.custom_bonus') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bonus-amount" class="form-label">{{ __('staff.payroll.bonus_amount') }}</label>
                    <input id="bonus-amount" 
                           type="number" 
                           x-model="bonusForm.amount" 
                           class="form-input"
                           step="0.01"
                           min="0"
                           placeholder="0.00">
                </div>

                <div class="form-group form-group--full">
                    <label for="bonus-description" class="form-label">{{ __('staff.payroll.bonus_description') }}</label>
                    <textarea id="bonus-description" 
                              x-model="bonusForm.description" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="{{ __('staff.payroll.bonus_description_placeholder') }}"></textarea>
                </div>

                <div class="form-group form-group--full">
                    <label class="form-label">{{ __('staff.payroll.select_employees') }}</label>
                    <div class="employee-selection">
                        <template x-for="employee in employees" :key="employee.id">
                            <label class="employee-checkbox">
                                <input type="checkbox" 
                                       :value="employee.id"
                                       x-model="bonusForm.selectedEmployees"
                                       class="form-checkbox">
                                <div class="employee-info">
                                    <img :src="employee.avatar" :alt="employee.name" class="employee-avatar-tiny">
                                    <span class="employee-name" x-text="employee.name"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="closeBonusModal()" class="btn btn-secondary">
                    {{ __('staff.payroll.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" :disabled="bonusForm.selectedEmployees.length === 0">
                    {{ __('staff.payroll.apply_bonuses') }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Deductions Management Modal -->
<div x-show="showDeductionsModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="modal-overlay"
     @click="closeDeductionsModal()">
    
    <div class="modal-container" @click.stop>
        <div class="modal-header">
            <h2 class="modal-title">{{ __('staff.payroll.manage_deductions') }}</h2>
            <button @click="closeDeductionsModal()" class="modal-close" aria-label="{{ __('staff.payroll.close_modal') }}">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form @submit.prevent="processDeductions()" class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="deduction-type" class="form-label">{{ __('staff.payroll.deduction_type') }}</label>
                    <select id="deduction-type" x-model="deductionForm.type" class="form-select">
                        <option value="tax">{{ __('staff.payroll.tax_deduction') }}</option>
                        <option value="insurance">{{ __('staff.payroll.insurance') }}</option>
                        <option value="loan">{{ __('staff.payroll.loan_repayment') }}</option>
                        <option value="advance">{{ __('staff.payroll.salary_advance') }}</option>
                        <option value="custom">{{ __('staff.payroll.custom_deduction') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="deduction-amount" class="form-label">{{ __('staff.payroll.deduction_amount') }}</label>
                    <input id="deduction-amount" 
                           type="number" 
                           x-model="deductionForm.amount" 
                           class="form-input"
                           step="0.01"
                           min="0"
                           placeholder="0.00">
                </div>

                <div class="form-group form-group--full">
                    <label for="deduction-description" class="form-label">{{ __('staff.payroll.deduction_description') }}</label>
                    <textarea id="deduction-description" 
                              x-model="deductionForm.description" 
                              class="form-textarea" 
                              rows="3"
                              placeholder="{{ __('staff.payroll.deduction_description_placeholder') }}"></textarea>
                </div>

                <div class="form-group form-group--full">
                    <label class="form-label">{{ __('staff.payroll.select_employees') }}</label>
                    <div class="employee-selection">
                        <template x-for="employee in employees" :key="employee.id">
                            <label class="employee-checkbox">
                                <input type="checkbox" 
                                       :value="employee.id"
                                       x-model="deductionForm.selectedEmployees"
                                       class="form-checkbox">
                                <div class="employee-info">
                                    <img :src="employee.avatar" :alt="employee.name" class="employee-avatar-tiny">
                                    <span class="employee-name" x-text="employee.name"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="closeDeductionsModal()" class="btn btn-secondary">
                    {{ __('staff.payroll.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" :disabled="deductionForm.selectedEmployees.length === 0">
                    {{ __('staff.payroll.apply_deductions') }}
                </button>
            </div>
        </form>
    </div>
</div>
