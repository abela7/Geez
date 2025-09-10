<!-- Employees View -->
<div x-show="currentView === 'employees'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     id="employees-view"
     role="tabpanel">
    
    <!-- Employee Filters & Search -->
    <div class="employee-filters">
        <div class="employee-search">
            <label for="employee-search" class="sr-only">{{ __('staff.payroll.search_employees') }}</label>
            <div class="search-input-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="employee-search" 
                       type="text" 
                       x-model="searchQuery" 
                       @input="searchEmployees()"
                       class="search-input" 
                       placeholder="{{ __('staff.payroll.search_placeholder') }}">
            </div>
        </div>

        <div class="employee-filter-controls">
            <select x-model="filters.department" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.payroll.all_departments') }}</option>
                <option value="kitchen">{{ __('staff.payroll.kitchen') }}</option>
                <option value="service">{{ __('staff.payroll.service') }}</option>
                <option value="management">{{ __('staff.payroll.management') }}</option>
                <option value="cleaning">{{ __('staff.payroll.cleaning') }}</option>
            </select>

            <select x-model="filters.payrollStatus" @change="applyFilters()" class="filter-select">
                <option value="">{{ __('staff.payroll.all_statuses') }}</option>
                <option value="active">{{ __('staff.payroll.active') }}</option>
                <option value="pending">{{ __('staff.payroll.pending') }}</option>
                <option value="processed">{{ __('staff.payroll.processed') }}</option>
            </select>

            <select x-model="sortBy" @change="sortEmployees()" class="filter-select">
                <option value="name">{{ __('staff.payroll.sort_name') }}</option>
                <option value="salary">{{ __('staff.payroll.sort_salary') }}</option>
                <option value="department">{{ __('staff.payroll.sort_department') }}</option>
                <option value="last_paid">{{ __('staff.payroll.sort_last_paid') }}</option>
            </select>

            <button @click="toggleSortOrder()" class="sort-toggle-btn" :class="{ 'desc': sortOrder === 'desc' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Employee Payroll Cards -->
    <div class="employee-payroll-grid">
        <template x-for="employee in filteredEmployees" :key="employee.id">
            <div class="employee-payroll-card" @click="viewEmployeePayroll(employee)">
                <div class="employee-card-header">
                    <div class="employee-avatar">
                        <img :src="employee.avatar" :alt="employee.name" class="avatar-image">
                    </div>
                    <div class="employee-info">
                        <h3 class="employee-name" x-text="employee.name"></h3>
                        <p class="employee-position" x-text="employee.position"></p>
                        <span class="employee-department" x-text="employee.department"></span>
                    </div>
                    <div class="employee-status">
                        <span class="status-badge" :class="`status-${employee.payroll_status}`" x-text="employee.payroll_status"></span>
                    </div>
                </div>

                <div class="employee-card-body">
                    <div class="salary-info">
                        <div class="salary-item">
                            <span class="salary-label">{{ __('staff.payroll.base_salary') }}</span>
                            <span class="salary-value" x-text="formatCurrency(employee.base_salary)"></span>
                        </div>
                        <div class="salary-item">
                            <span class="salary-label">{{ __('staff.payroll.bonuses') }}</span>
                            <span class="salary-value positive" x-text="formatCurrency(employee.bonuses)"></span>
                        </div>
                        <div class="salary-item">
                            <span class="salary-label">{{ __('staff.payroll.deductions') }}</span>
                            <span class="salary-value negative" x-text="formatCurrency(employee.deductions)"></span>
                        </div>
                        <div class="salary-item total">
                            <span class="salary-label">{{ __('staff.payroll.net_salary') }}</span>
                            <span class="salary-value" x-text="formatCurrency(employee.net_salary)"></span>
                        </div>
                    </div>

                    <div class="payroll-actions">
                        <button @click.stop="processEmployeePayroll(employee)" class="action-btn action-btn--primary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('staff.payroll.process') }}
                        </button>
                        
                        <button @click.stop="generatePayslip(employee)" class="action-btn action-btn--secondary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('staff.payroll.payslip') }}
                        </button>
                        
                        <button @click.stop="editEmployeePayroll(employee)" class="action-btn action-btn--tertiary">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            {{ __('staff.payroll.edit') }}
                        </button>
                    </div>
                </div>

                <div class="employee-card-footer">
                    <div class="last-paid">
                        <span class="last-paid-label">{{ __('staff.payroll.last_paid') }}:</span>
                        <span class="last-paid-date" x-text="employee.last_paid"></span>
                    </div>
                    <div class="next-payday">
                        <span class="next-payday-label">{{ __('staff.payroll.next_payday') }}:</span>
                        <span class="next-payday-date" x-text="employee.next_payday"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredEmployees.length === 0" class="empty-state">
        <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        <h3 class="empty-state-title">{{ __('staff.payroll.no_employees_found') }}</h3>
        <p class="empty-state-description">{{ __('staff.payroll.no_employees_description') }}</p>
    </div>

    <!-- Bulk Actions -->
    <div x-show="selectedEmployees.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bulk-actions">
        <div class="bulk-actions-content">
            <span class="bulk-actions-count" x-text="`${selectedEmployees.length} ${selectedEmployees.length === 1 ? '{{ __('staff.payroll.employee_selected') }}' : '{{ __('staff.payroll.employees_selected') }}'}`"></span>
            <div class="bulk-actions-buttons">
                <button @click="bulkProcessPayroll()" class="bulk-action-btn bulk-action-btn--primary">
                    {{ __('staff.payroll.process_selected') }}
                </button>
                <button @click="bulkGeneratePayslips()" class="bulk-action-btn bulk-action-btn--secondary">
                    {{ __('staff.payroll.generate_payslips') }}
                </button>
                <button @click="bulkAddBonus()" class="bulk-action-btn bulk-action-btn--success">
                    {{ __('staff.payroll.add_bonus') }}
                </button>
                <button @click="bulkAddDeduction()" class="bulk-action-btn bulk-action-btn--warning">
                    {{ __('staff.payroll.add_deduction') }}
                </button>
            </div>
        </div>
    </div>
</div>
