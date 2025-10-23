<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('admin.staff.payroll.periods') }}" 
                       class="p-1 rounded-lg hover:bg-opacity-10 transition-colors"
                       style="color: var(--color-text-muted);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Payroll Reports</h1>
                </div>
                <p class="text-sm" style="color: var(--color-text-secondary);">
                    Period: {{ $period->name }} ({{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }})
                </p>
            </div>
            <div>
                <button wire:click="exportSummary" 
                        type="button"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all inline-flex items-center gap-2 border"
                        style="background-color: var(--color-success); color: white;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Summary
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Total Staff</p>
            <p class="text-2xl font-bold" style="color: var(--color-text-primary);">{{ $summaryStats['total_staff'] }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Total Hours</p>
            <p class="text-2xl font-bold" style="color: var(--color-info);">
                @php
                    $totalMinutes = intval($summaryStats['total_hours'] * 60);
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;
                @endphp
                {{ $hours }}h {{ $minutes }}m
            </p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Gross Pay</p>
            <p class="text-2xl font-bold" style="color: var(--color-text-primary);">£{{ number_format($summaryStats['total_gross_pay'], 2) }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Net Pay</p>
            <p class="text-2xl font-bold" style="color: var(--color-success);">£{{ number_format($summaryStats['total_net_pay'], 2) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-xs font-medium mb-1" style="color: var(--color-text-secondary);">Search Staff</label>
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by name..."
                           class="w-full rounded-md shadow-sm pl-10 pr-4 py-2 transition-colors text-sm"
                           style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                    <svg class="absolute left-3 top-2.5 w-4 h-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-medium mb-1" style="color: var(--color-text-secondary);">Filter by Status</label>
                <select wire:model.live="statusFilter"
                        class="w-full rounded-md shadow-sm px-3 py-2 text-sm transition-colors"
                        style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                    <option value="all">All Statuses</option>
                    <option value="draft">Draft</option>
                    <option value="calculated">Calculated</option>
                    <option value="approved">Approved</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payroll Records Table -->
    <div class="rounded-lg shadow-md overflow-hidden" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
        @if($records->isEmpty())
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-lg font-medium mb-2" style="color: var(--color-text-primary);">No Records Found</h3>
                <p style="color: var(--color-text-secondary);">
                    No payroll records match your filters.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: var(--color-border-base);">
                    <thead style="background-color: var(--color-bg-tertiary);">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Staff Member</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Hours</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Gross Pay</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Deductions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Net Pay</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: var(--color-bg-secondary);">
                        @foreach($records as $record)
                            <tr class="transition-colors"
                                style="border-bottom: 1px solid var(--color-border-base);"
                                onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium" style="color: var(--color-text-primary);">
                                            {{ $record->staff->full_name ?? 'Unknown' }}
                                        </div>
                                        <div class="text-xs" style="color: var(--color-text-secondary);">
                                            {{ $record->staff->staffType->name ?? 'N/A' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right" style="color: var(--color-text-primary);">
                                    @php
                                        $totalMinutes = intval(($record->regular_hours + $record->overtime_hours) * 60);
                                        $hours = intdiv($totalMinutes, 60);
                                        $minutes = $totalMinutes % 60;
                                    @endphp
                                    {{ $hours }}h {{ $minutes }}m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium" style="color: var(--color-text-primary);">
                                    £{{ number_format($record->gross_pay, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right" style="color: var(--color-warning);">
                                    £{{ number_format($record->deductions ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold" style="color: var(--color-success);">
                                    £{{ number_format($record->net_pay, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                          style="{{ $record->status === 'draft' ? 'background-color: color-mix(in srgb, var(--color-text-muted) 20%, transparent); color: var(--color-text-muted);' : '' }}
                                                 {{ $record->status === 'calculated' ? 'background-color: var(--color-info-bg); color: var(--color-info);' : '' }}
                                                 {{ $record->status === 'approved' ? 'background-color: var(--color-success-bg); color: var(--color-success);' : '' }}
                                                 {{ $record->status === 'paid' ? 'background-color: color-mix(in srgb, var(--color-primary) 20%, transparent); color: var(--color-primary);' : '' }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <button wire:click="viewPayslip('{{ $record->id }}')"
                                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors inline-flex items-center gap-1"
                                            style="background-color: var(--color-info); color: white;">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Payslip
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t" style="border-color: var(--color-border-base);">
                {{ $records->links() }}
            </div>
        @endif
    </div>

    <!-- Payslip Modal -->
    @if($showPayslipModal && $selectedRecord)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"
             wire:click.self="closePayslipModal">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="rounded-lg shadow-xl max-w-2xl w-full" 
                     style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
                    
                    <!-- Payslip Header -->
                    <div class="p-6 border-b" style="border-color: var(--color-border-base); background-color: var(--color-bg-tertiary);">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold" style="color: var(--color-text-primary);">PAYSLIP</h2>
                                <p class="text-sm" style="color: var(--color-text-secondary);">{{ $period->name }}</p>
                            </div>
                            <button wire:click="closePayslipModal" 
                                    class="rounded-full p-1 hover:bg-opacity-10 transition-colors"
                                    style="color: var(--color-text-secondary);">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Payslip Body -->
                    <div class="p-6">
                        <!-- Employee Info -->
                        <div class="mb-6 pb-4 border-b" style="border-color: var(--color-border-base);">
                            <h3 class="text-sm font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Employee Information</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p style="color: var(--color-text-secondary);">Name</p>
                                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $selectedRecord->staff->full_name ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <p style="color: var(--color-text-secondary);">Employee ID</p>
                                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $selectedRecord->staff->profile->employee_id ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p style="color: var(--color-text-secondary);">Position</p>
                                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $selectedRecord->staff->staffType->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p style="color: var(--color-text-secondary);">Department</p>
                                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $selectedRecord->staff->profile->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Pay Period -->
                        <div class="mb-6 pb-4 border-b" style="border-color: var(--color-border-base);">
                            <h3 class="text-sm font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Pay Period</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p style="color: var(--color-text-secondary);">Period</p>
                                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p style="color: var(--color-text-secondary);">Pay Date</p>
                                    <p class="font-medium" style="color: var(--color-text-primary);">{{ $period->pay_date ? $period->pay_date->format('M d, Y') : 'Not set' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings -->
                        <div class="mb-6 pb-4 border-b" style="border-color: var(--color-border-base);">
                            <h3 class="text-sm font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Earnings</h3>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr class="border-b" style="border-color: var(--color-border-base);">
                                        <td class="py-2" style="color: var(--color-text-secondary);">Regular Hours ({{ number_format($selectedRecord->regular_hours, 2) }}h @ £{{ number_format($selectedRecord->hourly_rate, 2) }}/hr)</td>
                                        <td class="py-2 text-right font-medium" style="color: var(--color-text-primary);">£{{ number_format($selectedRecord->regular_pay ?? ($selectedRecord->regular_hours * $selectedRecord->hourly_rate), 2) }}</td>
                                    </tr>
                                    @if($selectedRecord->overtime_hours > 0)
                                        <tr class="border-b" style="border-color: var(--color-border-base);">
                                            <td class="py-2" style="color: var(--color-text-secondary);">Overtime Hours ({{ number_format($selectedRecord->overtime_hours, 2) }}h @ £{{ number_format($selectedRecord->hourly_rate * ($selectedRecord->overtime_rate ?? 1.5), 2) }}/hr)</td>
                                            <td class="py-2 text-right font-medium" style="color: var(--color-text-primary);">£{{ number_format($selectedRecord->overtime_pay ?? 0, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="py-2 font-semibold" style="color: var(--color-text-primary);">Gross Pay</td>
                                        <td class="py-2 text-right font-bold" style="color: var(--color-text-primary);">£{{ number_format($selectedRecord->gross_pay, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Deductions -->
                        <div class="mb-6 pb-4 border-b" style="border-color: var(--color-border-base);">
                            <h3 class="text-sm font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Deductions</h3>
                            <table class="w-full text-sm">
                                <tbody>
                                    <tr>
                                        <td class="py-2" style="color: var(--color-text-secondary);">Total Deductions</td>
                                        <td class="py-2 text-right font-medium" style="color: var(--color-warning);">£{{ number_format($selectedRecord->deductions ?? 0, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Net Pay -->
                        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-primary);">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-bold" style="color: var(--color-text-primary);">NET PAY</span>
                                <span class="text-2xl font-bold" style="color: var(--color-primary);">£{{ number_format($selectedRecord->net_pay, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payslip Footer -->
                    <div class="px-6 py-4 border-t flex justify-end gap-3" style="border-color: var(--color-border-base); background-color: var(--color-bg-tertiary);">
                        <button wire:click="closePayslipModal"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                            Close
                        </button>
                        <button wire:click="downloadPayslip('{{ $selectedRecord->id }}')"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors inline-flex items-center gap-2"
                                style="background-color: var(--color-primary); color: white;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

