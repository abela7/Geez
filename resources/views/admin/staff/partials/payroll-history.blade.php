<!-- History View -->
<div x-show="currentView === 'history'" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-4"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     id="history-view"
     role="tabpanel">
    
    <!-- History Filters -->
    <div class="history-filters">
        <div class="history-search">
            <label for="history-search" class="sr-only">{{ __('staff.payroll.search_history') }}</label>
            <div class="search-input-wrapper">
                <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="history-search" 
                       type="text" 
                       x-model="historySearchQuery" 
                       @input="searchHistory()"
                       class="search-input" 
                       placeholder="{{ __('staff.payroll.search_history_placeholder') }}">
            </div>
        </div>

        <div class="history-filter-controls">
            <select x-model="historyFilters.period" @change="applyHistoryFilters()" class="filter-select">
                <option value="">{{ __('staff.payroll.all_periods') }}</option>
                <option value="this_month">{{ __('staff.payroll.this_month') }}</option>
                <option value="last_month">{{ __('staff.payroll.last_month') }}</option>
                <option value="this_quarter">{{ __('staff.payroll.this_quarter') }}</option>
                <option value="this_year">{{ __('staff.payroll.this_year') }}</option>
                <option value="custom">{{ __('staff.payroll.custom_range') }}</option>
            </select>

            <select x-model="historyFilters.type" @change="applyHistoryFilters()" class="filter-select">
                <option value="">{{ __('staff.payroll.all_types') }}</option>
                <option value="salary">{{ __('staff.payroll.salary') }}</option>
                <option value="bonus">{{ __('staff.payroll.bonus') }}</option>
                <option value="deduction">{{ __('staff.payroll.deduction') }}</option>
                <option value="overtime">{{ __('staff.payroll.overtime') }}</option>
            </select>

            <select x-model="historyFilters.status" @change="applyHistoryFilters()" class="filter-select">
                <option value="">{{ __('staff.payroll.all_statuses') }}</option>
                <option value="processed">{{ __('staff.payroll.processed') }}</option>
                <option value="pending">{{ __('staff.payroll.pending') }}</option>
                <option value="cancelled">{{ __('staff.payroll.cancelled') }}</option>
            </select>

            <button @click="exportHistory()" class="btn btn-secondary btn-sm">
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                {{ __('staff.payroll.export') }}
            </button>
        </div>
    </div>

    <!-- Custom Date Range -->
    <div x-show="historyFilters.period === 'custom'" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 max-h-0"
         x-transition:enter-end="opacity-100 max-h-20"
         class="custom-date-range">
        <div class="date-range-inputs">
            <div class="date-input-group">
                <label for="start-date" class="date-label">{{ __('staff.payroll.start_date') }}</label>
                <input id="start-date" 
                       type="date" 
                       x-model="historyFilters.startDate" 
                       @change="applyHistoryFilters()"
                       class="date-input">
            </div>
            <div class="date-input-group">
                <label for="end-date" class="date-label">{{ __('staff.payroll.end_date') }}</label>
                <input id="end-date" 
                       type="date" 
                       x-model="historyFilters.endDate" 
                       @change="applyHistoryFilters()"
                       class="date-input">
            </div>
        </div>
    </div>

    <!-- Payroll History Table -->
    <div class="history-table-container">
        <table class="history-table">
            <thead>
                <tr>
                    <th class="table-header table-header--checkbox">
                        <input type="checkbox" 
                               @change="toggleSelectAllHistory()"
                               :checked="selectedHistory.length === filteredHistory.length && filteredHistory.length > 0"
                               class="table-checkbox"
                               aria-label="{{ __('staff.payroll.select_all') }}">
                    </th>
                    <th class="table-header">{{ __('staff.payroll.employee') }}</th>
                    <th class="table-header">{{ __('staff.payroll.period') }}</th>
                    <th class="table-header">{{ __('staff.payroll.type') }}</th>
                    <th class="table-header">{{ __('staff.payroll.gross_amount') }}</th>
                    <th class="table-header">{{ __('staff.payroll.deductions') }}</th>
                    <th class="table-header">{{ __('staff.payroll.net_amount') }}</th>
                    <th class="table-header">{{ __('staff.payroll.status') }}</th>
                    <th class="table-header">{{ __('staff.payroll.processed_date') }}</th>
                    <th class="table-header table-header--actions">{{ __('staff.payroll.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="record in filteredHistory" :key="record.id">
                    <tr class="table-row" :class="{ 'selected': selectedHistory.includes(record.id) }">
                        <td class="table-cell table-cell--checkbox">
                            <input type="checkbox" 
                                   :value="record.id"
                                   @change="toggleHistorySelection(record.id)"
                                   :checked="selectedHistory.includes(record.id)"
                                   class="table-checkbox"
                                   :aria-label="`Select ${record.employee_name}`">
                        </td>
                        <td class="table-cell">
                            <div class="employee-cell">
                                <img :src="record.employee_avatar" :alt="record.employee_name" class="employee-avatar-small">
                                <div class="employee-details">
                                    <span class="employee-name" x-text="record.employee_name"></span>
                                    <span class="employee-id" x-text="record.employee_id"></span>
                                </div>
                            </div>
                        </td>
                        <td class="table-cell">
                            <span class="period-text" x-text="record.period"></span>
                        </td>
                        <td class="table-cell">
                            <span class="type-badge" :class="`type-${record.type}`" x-text="record.type"></span>
                        </td>
                        <td class="table-cell">
                            <span class="amount-text" x-text="formatCurrency(record.gross_amount)"></span>
                        </td>
                        <td class="table-cell">
                            <span class="amount-text negative" x-text="formatCurrency(record.deductions)"></span>
                        </td>
                        <td class="table-cell">
                            <span class="amount-text primary" x-text="formatCurrency(record.net_amount)"></span>
                        </td>
                        <td class="table-cell">
                            <span class="status-badge" :class="`status-${record.status}`" x-text="record.status"></span>
                        </td>
                        <td class="table-cell">
                            <span class="date-text" x-text="record.processed_date"></span>
                        </td>
                        <td class="table-cell table-cell--actions">
                            <div class="table-actions">
                                <button @click="viewPayrollRecord(record)" class="table-action" aria-label="{{ __('staff.payroll.view_record') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>
                                <button @click="downloadPayslip(record)" class="table-action" aria-label="{{ __('staff.payroll.download_payslip') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>
                                <button x-show="record.status === 'pending'" @click="cancelPayroll(record)" class="table-action table-action--danger" aria-label="{{ __('staff.payroll.cancel_payroll') }}">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <!-- Empty State -->
        <div x-show="filteredHistory.length === 0" class="empty-state">
            <svg class="empty-state-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="empty-state-title">{{ __('staff.payroll.no_history_found') }}</h3>
            <p class="empty-state-description">{{ __('staff.payroll.no_history_description') }}</p>
        </div>
    </div>

    <!-- Pagination -->
    <div x-show="filteredHistory.length > 0" class="pagination">
        <div class="pagination-info">
            <span x-text="`{{ __('staff.payroll.showing') }} ${(currentPage - 1) * itemsPerPage + 1}-${Math.min(currentPage * itemsPerPage, filteredHistory.length)} {{ __('staff.payroll.of') }} ${filteredHistory.length} {{ __('staff.payroll.records') }}`"></span>
        </div>
        <div class="pagination-controls">
            <button @click="previousPage()" 
                    :disabled="currentPage === 1"
                    class="pagination-btn">
                {{ __('staff.payroll.previous') }}
            </button>
            <template x-for="page in paginationPages" :key="page">
                <button @click="goToPage(page)" 
                        :class="{ 'active': page === currentPage }"
                        class="pagination-btn"
                        x-text="page"></button>
            </template>
            <button @click="nextPage()" 
                    :disabled="currentPage === totalPages"
                    class="pagination-btn">
                {{ __('staff.payroll.next') }}
            </button>
        </div>
    </div>

    <!-- Bulk History Actions -->
    <div x-show="selectedHistory.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="bulk-actions">
        <div class="bulk-actions-content">
            <span class="bulk-actions-count" x-text="`${selectedHistory.length} ${selectedHistory.length === 1 ? '{{ __('staff.payroll.record_selected') }}' : '{{ __('staff.payroll.records_selected') }}'}`"></span>
            <div class="bulk-actions-buttons">
                <button @click="bulkDownloadPayslips()" class="bulk-action-btn bulk-action-btn--primary">
                    {{ __('staff.payroll.download_payslips') }}
                </button>
                <button @click="bulkExportRecords()" class="bulk-action-btn bulk-action-btn--secondary">
                    {{ __('staff.payroll.export_records') }}
                </button>
                <button @click="bulkCancelPayroll()" class="bulk-action-btn bulk-action-btn--danger">
                    {{ __('staff.payroll.cancel_selected') }}
                </button>
            </div>
        </div>
    </div>
</div>
