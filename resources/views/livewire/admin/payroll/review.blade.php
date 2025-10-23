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
                    <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Review Payroll</h1>
                </div>
                <p class="text-sm" style="color: var(--color-text-secondary);">
                    Period: {{ $period->name }} ({{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }})
                </p>
            </div>
            <div class="flex gap-2">
                <button wire:click="recalculateAll" 
                        type="button"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all inline-flex items-center gap-2 border"
                        style="background-color: var(--color-bg-secondary); border-color: var(--color-border-base); color: var(--color-text-primary);"
                        onmouseover="this.style.backgroundColor='var(--color-bg-tertiary)';"
                        onmouseout="this.style.backgroundColor='var(--color-bg-secondary)';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Recalculate All
                </button>
                <button wire:click="openApproveModal" 
                        type="button"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-all inline-flex items-center gap-2"
                        style="background-color: var(--color-success); color: white;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Approve Selected ({{ count($selectedRecords) }})
                </button>
            </div>
        </div>
    </div>

    <!-- Status Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Draft</p>
            <p class="text-2xl font-bold" style="color: var(--color-text-primary);">{{ $statusStats['draft'] }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Calculated</p>
            <p class="text-2xl font-bold" style="color: var(--color-info);">{{ $statusStats['calculated'] }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Needs Review</p>
            <p class="text-2xl font-bold" style="color: var(--color-warning);">{{ $statusStats['needs_review'] }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Approved</p>
            <p class="text-2xl font-bold" style="color: var(--color-success);">{{ $statusStats['approved'] }}</p>
        </div>
        <div class="p-4 rounded-lg" style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
            <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Paid</p>
            <p class="text-2xl font-bold" style="color: var(--color-primary);">{{ $statusStats['paid'] }}</p>
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
                    <option value="needs_review">Needs Review</option>
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
                <h3 class="text-lg font-medium mb-2" style="color: var(--color-text-primary);">No Payroll Records Found</h3>
                <p style="color: var(--color-text-secondary);">
                    @if($search || $statusFilter !== 'all')
                        No records match your filters. Try adjusting your search criteria.
                    @else
                        No payroll records have been generated for this period yet.
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y" style="border-color: var(--color-border-base);">
                    <thead style="background-color: var(--color-bg-tertiary);">
                        <tr>
                            <th class="px-4 py-3 text-left">
                                <input type="checkbox" 
                                       wire:model.live="selectAll"
                                       class="rounded transition-colors"
                                       style="border-color: var(--color-border-base);">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Staff Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Hours</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Gross Pay</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Deductions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Net Pay</th>
                            <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Actions</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: var(--color-bg-secondary);">
                        @foreach($records as $record)
                            <tr class="transition-colors"
                                style="border-bottom: 1px solid var(--color-border-base);"
                                onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                onmouseout="this.style.backgroundColor='transparent'">
                                <td class="px-4 py-4">
                                    @if(in_array($record->status, ['calculated', 'needs_review']))
                                        <input type="checkbox" 
                                               wire:click="toggleRecordSelection('{{ $record->id }}')"
                                               @checked(in_array($record->id, $selectedRecords))
                                               class="rounded transition-colors"
                                               style="border-color: var(--color-border-base);">
                                    @endif
                                </td>
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                    @php
                                        $totalMinutes = intval(($record->regular_hours + $record->overtime_hours) * 60);
                                        $hours = intdiv($totalMinutes, 60);
                                        $minutes = $totalMinutes % 60;
                                    @endphp
                                    <div>{{ $hours }}h {{ $minutes }}m</div>
                                    @if($record->overtime_hours > 0)
                                        <div class="text-xs" style="color: var(--color-warning);">
                                            @php
                                                $otMinutes = intval($record->overtime_hours * 60);
                                                $otHours = intdiv($otMinutes, 60);
                                                $otMins = $otMinutes % 60;
                                            @endphp
                                            +{{ $otHours }}h {{ $otMins }}m OT
                                        </div>
                                    @endif
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
                                                 {{ $record->status === 'needs_review' ? 'background-color: var(--color-warning-bg); color: var(--color-warning);' : '' }}
                                                 {{ $record->status === 'approved' ? 'background-color: var(--color-success-bg); color: var(--color-success);' : '' }}
                                                 {{ $record->status === 'paid' ? 'background-color: color-mix(in srgb, var(--color-primary) 20%, transparent); color: var(--color-primary);' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(in_array($record->status, ['calculated', 'needs_review']))
                                            <button wire:click="openRecalculateModal('{{ $record->id }}')"
                                                    class="p-1.5 rounded-lg transition-colors"
                                                    style="color: var(--color-info); background-color: var(--color-info-bg);"
                                                    title="Recalculate">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </button>
                                            <button wire:click="rejectRecord('{{ $record->id }}')"
                                                    class="p-1.5 rounded-lg transition-colors"
                                                    style="color: var(--color-warning); background-color: var(--color-warning-bg);"
                                                    title="Mark as Needs Review">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                </svg>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.staff.payroll.periods.records', $period->id) }}"
                                           class="p-1.5 rounded-lg transition-colors"
                                           style="color: var(--color-text-secondary); background-color: var(--color-bg-tertiary);"
                                           title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recalculate Modal -->
    @if($showRecalculateModal && $recalculatingRecord)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"
             wire:click.self="closeRecalculateModal">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="rounded-lg shadow-xl max-w-md w-full p-6" 
                     style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold" style="color: var(--color-text-primary);">
                            Recalculate Payroll
                        </h3>
                        <button wire:click="closeRecalculateModal" 
                                class="rounded-full p-1 hover:bg-opacity-10 transition-colors"
                                style="color: var(--color-text-secondary);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-sm mb-4" style="color: var(--color-text-secondary);">
                            Are you sure you want to recalculate payroll for <strong style="color: var(--color-text-primary);">{{ $recalculatingRecord->staff->full_name ?? 'Unknown' }}</strong>?
                        </p>
                        <p class="text-sm" style="color: var(--color-text-secondary);">
                            This will update the pay calculation based on current settings and hourly rate. The status will be reset to "Calculated".
                        </p>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                wire:click="closeRecalculateModal"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);">
                            Cancel
                        </button>
                        <button type="button"
                                wire:click="recalculateRecord"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                style="background-color: var(--color-info); color: white;">
                            <span wire:loading.remove>Recalculate</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Approve Modal -->
    @if($showApproveModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"
             wire:click.self="closeApproveModal">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="rounded-lg shadow-xl max-w-md w-full p-6" 
                     style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold" style="color: var(--color-text-primary);">
                            Approve Payroll Records
                        </h3>
                        <button wire:click="closeApproveModal" 
                                class="rounded-full p-1 hover:bg-opacity-10 transition-colors"
                                style="color: var(--color-text-secondary);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="mb-6">
                        <p class="text-sm mb-4" style="color: var(--color-text-secondary);">
                            You are about to approve <strong style="color: var(--color-text-primary);">{{ count($selectedRecords) }}</strong> payroll record(s).
                        </p>
                        <p class="text-sm mb-4" style="color: var(--color-text-secondary);">
                            Once approved, these records will be ready for payment processing.
                        </p>

                        <!-- Optional Notes -->
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">
                                Approval Notes (Optional)
                            </label>
                            <textarea wire:model="approvalNotes" 
                                      rows="3"
                                      placeholder="Add any notes about this approval..."
                                      class="w-full rounded-lg shadow-sm px-3 py-2 text-sm"
                                      style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3">
                        <button type="button" 
                                wire:click="closeApproveModal"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);">
                            Cancel
                        </button>
                        <button type="button"
                                wire:click="approveSelected"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors"
                                style="background-color: var(--color-success); color: white;">
                            <span wire:loading.remove>Approve Records</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

