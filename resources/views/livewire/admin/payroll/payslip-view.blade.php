<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('admin.staff.payroll.reports', ['period' => $record->pay_period_id]) }}" 
                       class="p-1 rounded-lg hover:bg-opacity-10 transition-colors"
                       style="color: var(--color-text-muted);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Employee Payslip</h1>
                </div>
                <p class="text-sm" style="color: var(--color-text-secondary);">
                    {{ $record->payPeriod->period_start->format('M d') }} - {{ $record->payPeriod->period_end->format('M d, Y') }}
                </p>
            </div>
            <div class="flex gap-3">
                <button wire:click="downloadPayslip" 
                        type="button"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all inline-flex items-center gap-2"
                        style="background-color: var(--color-primary); color: white;"
                        onmouseover="this.style.opacity='0.9';"
                        onmouseout="this.style.opacity='1';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>
    </div>

    <!-- Payslip Content -->
    <div class="max-w-4xl mx-auto">
        <div class="rounded-lg shadow-md p-6 space-y-8" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            
            <!-- Employee Details Section -->
            <div>
                <h2 class="text-lg font-bold mb-4 pb-2 border-b" style="color: var(--color-text-primary); border-color: var(--color-border-base);">
                    Employee Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: var(--color-text-secondary);">Name</p>
                        <p class="text-base font-medium" style="color: var(--color-text-primary);">{{ $record->staff->full_name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: var(--color-text-secondary);">Employee ID</p>
                        <p class="text-base font-medium" style="color: var(--color-text-primary);">{{ $record->staff->profile->employee_id ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: var(--color-text-secondary);">Position</p>
                        <p class="text-base font-medium" style="color: var(--color-text-primary);">{{ $record->staff->staffType->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide mb-2" style="color: var(--color-text-secondary);">Pay Date</p>
                        <p class="text-base font-medium" style="color: var(--color-text-primary);">
                            {{ $record->payPeriod->pay_date ? $record->payPeriod->pay_date->format('M d, Y') : 'Not set' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Earnings Section -->
            <div>
                <h2 class="text-lg font-bold mb-4 pb-2 border-b" style="color: var(--color-text-primary); border-color: var(--color-border-base);">
                    Earnings
                </h2>
                <div class="space-y-3">
                    <div class="flex items-center justify-between py-3 px-4 rounded-lg" style="background-color: var(--color-bg-tertiary);">
                        <div>
                            <p class="font-medium" style="color: var(--color-text-primary);">Regular Hours</p>
                            <p class="text-sm" style="color: var(--color-text-secondary);">
                                {{ number_format($record->regular_hours, 2) }}h @ £{{ number_format($record->hourly_rate, 2) }}/hr
                            </p>
                        </div>
                        <span class="text-lg font-semibold" style="color: var(--color-text-primary);">
                            £{{ number_format($record->regular_pay ?? ($record->regular_hours * $record->hourly_rate), 2) }}
                        </span>
                    </div>
                    
                    @if($record->overtime_hours > 0)
                    <div class="flex items-center justify-between py-3 px-4 rounded-lg" style="background-color: var(--color-bg-tertiary);">
                        <div>
                            <p class="font-medium" style="color: var(--color-text-primary);">Overtime Hours</p>
                            <p class="text-sm" style="color: var(--color-text-secondary);">
                                {{ number_format($record->overtime_hours, 2) }}h @ £{{ number_format($record->overtime_rate ?? ($record->hourly_rate * 1.5), 2) }}/hr
                            </p>
                        </div>
                        <span class="text-lg font-semibold" style="color: var(--color-text-primary);">
                            £{{ number_format($record->overtime_pay ?? 0, 2) }}
                        </span>
                    </div>
                    @endif

                    @if($record->bonus_total > 0)
                    <div class="flex items-center justify-between py-3 px-4 rounded-lg" style="background-color: var(--color-bg-tertiary);">
                        <div>
                            <p class="font-medium" style="color: var(--color-text-primary);">Bonuses</p>
                        </div>
                        <span class="text-lg font-semibold" style="color: var(--color-text-primary);">
                            £{{ number_format($record->bonus_total, 2) }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between py-4 px-4 border-t-2" style="border-color: var(--color-border-base);">
                        <span class="text-lg font-bold" style="color: var(--color-text-primary);">Gross Pay</span>
                        <span class="text-2xl font-bold" style="color: var(--color-text-primary);">
                            £{{ number_format($record->gross_pay, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Deductions Section -->
            <div>
                <h2 class="text-lg font-bold mb-4 pb-2 border-b" style="color: var(--color-text-primary); border-color: var(--color-border-base);">
                    Deductions
                </h2>
                <div class="space-y-3">
                    @if($record->tax_deductions > 0)
                    <div class="flex items-center justify-between py-3 px-4 rounded-lg" style="background-color: var(--color-bg-tertiary);">
                        <p class="font-medium" style="color: var(--color-text-primary);">Tax Deductions</p>
                        <span class="text-lg font-semibold" style="color: var(--color-warning);">
                            £{{ number_format($record->tax_deductions, 2) }}
                        </span>
                    </div>
                    @endif
                    
                    @if($record->other_deductions > 0)
                    <div class="flex items-center justify-between py-3 px-4 rounded-lg" style="background-color: var(--color-bg-tertiary);">
                        <p class="font-medium" style="color: var(--color-text-primary);">Other Deductions</p>
                        <span class="text-lg font-semibold" style="color: var(--color-warning);">
                            £{{ number_format($record->other_deductions, 2) }}
                        </span>
                    </div>
                    @endif
                    
                    <div class="flex items-center justify-between py-4 px-4 border-t-2" style="border-color: var(--color-border-base);">
                        <span class="text-lg font-bold" style="color: var(--color-text-primary);">Total Deductions</span>
                        <span class="text-2xl font-bold" style="color: var(--color-warning);">
                            £{{ number_format($record->deductions ?? 0, 2) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Net Pay Section - Highlighted -->
            <div class="p-6 rounded-lg" style="background-color: var(--color-success-bg); border: 3px solid var(--color-success);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide mb-1" style="color: var(--color-success);">Net Pay</p>
                        <p class="text-xs" style="color: var(--color-text-secondary);">Amount to be paid</p>
                    </div>
                    <span class="text-4xl font-bold" style="color: var(--color-success);">
                        £{{ number_format($record->net_pay, 2) }}
                    </span>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="pt-4 border-t" style="border-color: var(--color-border-base);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold mb-1" style="color: var(--color-text-secondary);">Payment Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                              style="{{ $record->status === 'draft' ? 'background-color: color-mix(in srgb, var(--color-text-muted) 20%, transparent); color: var(--color-text-muted);' : '' }}
                                     {{ $record->status === 'calculated' ? 'background-color: var(--color-info-bg); color: var(--color-info);' : '' }}
                                     {{ $record->status === 'approved' ? 'background-color: var(--color-success-bg); color: var(--color-success);' : '' }}
                                     {{ $record->status === 'paid' ? 'background-color: color-mix(in srgb, var(--color-primary) 20%, transparent); color: var(--color-primary);' : '' }}">
                            {{ ucfirst($record->status) }}
                        </span>
                    </div>
                    @if($record->processed_at)
                    <div class="text-right">
                        <p class="text-sm font-semibold mb-1" style="color: var(--color-text-secondary);">Processed Date</p>
                        <p class="text-sm" style="color: var(--color-text-primary);">{{ $record->processed_at->format('M d, Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            @if($record->notes)
            <!-- Notes Section -->
            <div class="pt-4 border-t" style="border-color: var(--color-border-base);">
                <p class="text-sm font-semibold mb-2" style="color: var(--color-text-secondary);">Notes</p>
                <p class="text-sm p-3 rounded-lg" style="color: var(--color-text-primary); background-color: var(--color-bg-tertiary);">
                    {{ $record->notes }}
                </p>
            </div>
            @endif
        </div>
    </div>
</div>
