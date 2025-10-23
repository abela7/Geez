<div>
    {{-- Page Header --}}
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2" style="color: var(--color-text-primary);">
                    Payroll Records
                </h1>
                <p style="color: var(--color-text-secondary);">
                    {{ $period->name }} ({{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }})
                </p>
            </div>
            <a href="{{ route('admin.staff.payroll.add') }}" 
               class="btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg transition-all"
               style="background: var(--color-primary); color: white;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add More Records
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="rounded-xl p-6 border transition-all duration-200"
             style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Total Staff</p>
                    <p class="text-2xl font-bold" style="color: var(--color-text-primary);">
                        {{ $period->total_staff_count ?? 0 }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background: var(--color-info-bg);">
                    <svg class="w-6 h-6" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl p-6 border transition-all duration-200"
             style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Gross Pay</p>
                    <p class="text-2xl font-bold" style="color: var(--color-text-primary);">
                        £{{ number_format($period->total_gross_pay ?? 0, 2) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background: var(--color-success-bg);">
                    <svg class="w-6 h-6" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl p-6 border transition-all duration-200"
             style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Deductions</p>
                    <p class="text-2xl font-bold" style="color: var(--color-text-primary);">
                        £{{ number_format($period->total_deductions ?? 0, 2) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background: var(--color-warning-bg);">
                    <svg class="w-6 h-6" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl p-6 border transition-all duration-200"
             style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm mb-1" style="color: var(--color-text-secondary);">Net Pay</p>
                    <p class="text-2xl font-bold" style="color: var(--color-primary);">
                        £{{ number_format($period->total_net_pay ?? 0, 2) }}
                    </p>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center"
                     style="background: color-mix(in srgb, var(--color-primary) 10%, transparent);">
                    <svg class="w-6 h-6" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="rounded-xl p-6 mb-6 border"
         style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                    Search Staff
                </label>
                <input type="text"
                       wire:model.live.debounce.300ms="search"
                       placeholder="Search by name..."
                       class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                       style="background: var(--color-bg-primary); 
                              border-color: var(--color-border-base); 
                              color: var(--color-text-primary);
                              --tw-ring-color: var(--color-primary);">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                    Status Filter
                </label>
                <select wire:model.live="statusFilter"
                        class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                        style="background: var(--color-bg-primary); 
                               border-color: var(--color-border-base); 
                               color: var(--color-text-primary);
                               --tw-ring-color: var(--color-primary);">
                    <option value="all">All Status</option>
                    <option value="draft">Draft</option>
                    <option value="calculated">Calculated</option>
                    <option value="approved">Approved</option>
                    <option value="paid">Paid</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Records Table --}}
    <div class="rounded-xl border overflow-hidden"
         style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead style="background: var(--color-bg-tertiary); position: sticky; top: 0; z-index: 10;">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                            Staff
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                            Total Hours
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                            Gross Pay
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                            Status
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--color-border-base);">
                    @forelse($records as $record)
                        <tr class="transition-colors hover:bg-opacity-50"
                            style="--hover-bg: var(--color-bg-tertiary);">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium" style="color: var(--color-text-primary);">
                                        {{ $record->staff->full_name ?? 'Unknown' }}
                                    </p>
                                    <p class="text-sm" style="color: var(--color-text-muted);">
                                        {{ $record->staff->profile->employee_id ?? 'No ID' }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                @php
                                    $regularMinutes = intval($record->regular_hours * 60);
                                    $regularHours = intdiv($regularMinutes, 60);
                                    $regularMins = $regularMinutes % 60;
                                    
                                    $overtimeMinutes = intval($record->overtime_hours * 60);
                                    $overtimeHours = intdiv($overtimeMinutes, 60);
                                    $overtimeMins = $overtimeMinutes % 60;
                                    
                                    $totalHours = $record->regular_hours + $record->overtime_hours;
                                @endphp
                                <span class="font-medium">{{ sprintf('%.1f', $totalHours) }}h</span>
                                <span style="color: var(--color-text-secondary);" class="text-xs">({{ $regularHours }}h {{ $regularMins }}m + {{ $overtimeHours }}h {{ $overtimeMins }}m)</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-primary);">
                                £{{ number_format($record->gross_pay, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="{{ $record->status === 'draft' ? 'background-color: color-mix(in srgb, var(--color-text-muted) 20%, transparent); color: var(--color-text-muted);' : '' }}
                                             {{ $record->status === 'calculated' ? 'background-color: var(--color-info-bg); color: var(--color-info);' : '' }}
                                             {{ $record->status === 'approved' ? 'background-color: var(--color-warning-bg); color: var(--color-warning);' : '' }}
                                             {{ $record->status === 'paid' ? 'background-color: var(--color-success-bg); color: var(--color-success);' : '' }}">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="viewDetail('{{ $record->id }}')"
                                            class="p-2 rounded-lg transition-all hover:bg-opacity-10"
                                            style="color: var(--color-info); --hover-bg: var(--color-info);"
                                            title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="openEditModal('{{ $record->id }}')"
                                            class="p-2 rounded-lg transition-all hover:bg-opacity-10"
                                            style="color: var(--color-warning); --hover-bg: var(--color-warning);"
                                            title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="confirmDelete('{{ $record->id }}')"
                                            class="p-2 rounded-lg transition-all hover:bg-opacity-10"
                                            style="color: var(--color-error); --hover-bg: var(--color-error);"
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 mb-4 opacity-50" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium mb-2" style="color: var(--color-text-primary);">
                                        No payroll records found
                                    </p>
                                    <p style="color: var(--color-text-muted);">
                                        Start by adding payroll records for this period
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records->hasPages())
            <div class="px-6 py-4 border-t" style="border-color: var(--color-border-base);">
                {{ $records->links() }}
            </div>
        @endif
    </div>

    {{-- Edit Modal --}}
    @if($showEditModal && $editingRecord)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="backdrop-filter: blur(4px); background-color: var(--modal-overlay, rgba(0, 0, 0, 0.5));">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="rounded-xl shadow-2xl w-full max-w-2xl border"
                     style="background: var(--color-surface-card, var(--color-bg-secondary)); 
                            border-color: var(--color-border-base);
                            z-index: var(--z-modal, 1000);">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between px-8 py-6 border-b" style="border-color: var(--color-border-base);">
                        <div>
                            <h3 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                                Edit Payroll Record
                            </h3>
                            <p class="mt-1" style="color: var(--color-text-secondary);">
                                {{ $editingRecord->staff->full_name ?? 'Unknown' }}
                            </p>
                        </div>
                        <button wire:click="closeEditModal"
                                class="p-2 rounded-lg transition-colors hover:bg-opacity-10"
                                style="color: var(--color-text-muted); --hover-bg: var(--color-text-muted);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="px-8 py-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                    Regular Hours
                                </label>
                                <input type="number"
                                       wire:model="editRegularHours"
                                       wire:change="recalculateAmounts"
                                       step="0.01"
                                       class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                       style="background: var(--color-bg-primary); 
                                              border-color: var(--color-border-base); 
                                              color: var(--color-text-primary);
                                              --tw-ring-color: var(--color-primary);">
                                @error('editRegularHours')
                                    <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                    Overtime Hours
                                </label>
                                <input type="number"
                                       wire:model="editOvertimeHours"
                                       wire:change="recalculateAmounts"
                                       step="0.01"
                                       class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                       style="background: var(--color-bg-primary); 
                                              border-color: var(--color-border-base); 
                                              color: var(--color-text-primary);
                                              --tw-ring-color: var(--color-primary);">
                                @error('editOvertimeHours')
                                    <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                Hourly Rate (£)
                            </label>
                            <input type="number"
                                   wire:model="editHourlyRate"
                                   wire:change="recalculateAmounts"
                                   step="0.01"
                                   class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                   style="background: var(--color-bg-primary); 
                                          border-color: var(--color-border-base); 
                                          color: var(--color-text-primary);
                                          --tw-ring-color: var(--color-primary);">
                            @error('editHourlyRate')
                                <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                    Gross Pay (£)
                                </label>
                                <input type="number"
                                       wire:model="editGrossPay"
                                       step="0.01"
                                       class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                       style="background: var(--color-bg-primary); 
                                              border-color: var(--color-border-base); 
                                              color: var(--color-text-primary);
                                              --tw-ring-color: var(--color-primary);">
                                @error('editGrossPay')
                                    <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                    Deductions (£)
                                </label>
                                <input type="number"
                                       wire:model="editDeductions"
                                       wire:change="recalculateAmounts"
                                       step="0.01"
                                       class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                       style="background: var(--color-bg-primary); 
                                              border-color: var(--color-border-base); 
                                              color: var(--color-text-primary);
                                              --tw-ring-color: var(--color-primary);">
                                @error('editDeductions')
                                    <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                    Net Pay (£)
                                </label>
                                <input type="number"
                                       wire:model="editNetPay"
                                       step="0.01"
                                       readonly
                                       class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                       style="background: var(--color-bg-tertiary); 
                                              border-color: var(--color-border-base); 
                                              color: var(--color-text-primary);
                                              --tw-ring-color: var(--color-primary);">
                                @error('editNetPay')
                                    <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: var(--color-text-secondary);">
                                Notes (Optional)
                            </label>
                            <textarea wire:model="editNotes"
                                      rows="3"
                                      placeholder="Add any notes about this payroll record..."
                                      class="w-full px-4 py-2 rounded-lg border transition-colors focus:outline-none focus:ring-2"
                                      style="background: var(--color-bg-primary); 
                                             border-color: var(--color-border-base); 
                                             color: var(--color-text-primary);
                                             --tw-ring-color: var(--color-primary);"></textarea>
                            @error('editNotes')
                                <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex items-center justify-end gap-3 px-8 py-6 border-t" style="border-color: var(--color-border-base);">
                        <button wire:click="closeEditModal"
                                type="button"
                                class="px-6 py-2.5 rounded-lg font-medium transition-all border"
                                style="background: transparent; 
                                       border-color: var(--color-border-base);
                                       color: var(--color-text-secondary);">
                            Cancel
                        </button>
                        <button wire:click="saveRecord"
                                wire:loading.attr="disabled"
                                type="button"
                                class="btn-primary px-6 py-2.5 rounded-lg font-medium transition-all inline-flex items-center gap-2"
                                style="background: var(--color-primary); color: white;">
                            <span wire:loading.remove wire:target="saveRecord">Save Changes</span>
                            <span wire:loading wire:target="saveRecord" class="inline-flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Saving...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($showDeleteModal && $recordToDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" style="backdrop-filter: blur(4px); background-color: var(--modal-overlay, rgba(0, 0, 0, 0.5));">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="rounded-xl shadow-2xl w-full max-w-md border"
                     style="background: var(--color-surface-card, var(--color-bg-secondary)); 
                            border-color: var(--color-border-base);
                            z-index: var(--z-modal, 1000);">
                    <div class="px-8 py-6">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center"
                                 style="background: var(--color-error-bg);">
                                <svg class="w-6 h-6" style="color: var(--color-error);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold" style="color: var(--color-text-primary);">Delete Payroll Record</h3>
                                <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">This action cannot be undone</p>
                            </div>
                        </div>

                        <p class="mb-6" style="color: var(--color-text-primary);">
                            Are you sure you want to delete the payroll record for 
                            <strong>{{ $recordToDelete->staff->full_name ?? 'Unknown' }}</strong>?
                        </p>

                        <div class="flex items-center justify-end gap-3">
                            <button wire:click="cancelDelete"
                                    type="button"
                                    class="px-6 py-2.5 rounded-lg font-medium transition-all border"
                                    style="background: transparent; 
                                           border-color: var(--color-border-base);
                                           color: var(--color-text-secondary);">
                                Cancel
                            </button>
                            <button wire:click="deleteRecord"
                                    wire:loading.attr="disabled"
                                    type="button"
                                    class="px-6 py-2.5 rounded-lg font-medium transition-all inline-flex items-center gap-2"
                                    style="background: var(--color-error); color: white;">
                                <span wire:loading.remove wire:target="deleteRecord">Delete</span>
                                <span wire:loading wire:target="deleteRecord" class="inline-flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Deleting...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail View Modal --}}
    @if($showDetailModal && $viewingRecord)
        <div class="fixed inset-0 z-50" style="backdrop-filter: blur(4px); background-color: var(--modal-overlay, rgba(0, 0, 0, 0.3)); pointer-events: none;">
            {{-- Backdrop Click to Close --}}
            <div wire:click="closeDetailModal" class="fixed inset-0 cursor-pointer" style="pointer-events: all;"></div>

            {{-- Side Panel --}}
            <div class="fixed right-0 top-0 bottom-0 w-full md:w-[500px] border-l overflow-y-auto transition-all duration-300 z-50"
                 style="background: var(--color-bg-primary); border-color: var(--color-border-base); box-shadow: -2px 0 8px rgba(0, 0, 0, 0.15); pointer-events: all;">
                
                {{-- Header --}}
                <div class="sticky top-0 border-b px-6 py-4 flex items-center justify-between" style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
                    <div>
                        <h3 class="font-bold" style="color: var(--color-text-primary);">
                            Payroll Details
                        </h3>
                        <p class="text-sm" style="color: var(--color-text-secondary);">
                            {{ $viewingRecord->staff->full_name ?? 'Unknown' }}
                        </p>
                    </div>
                    <button wire:click="closeDetailModal"
                            class="p-2 rounded-lg transition-colors"
                            style="color: var(--color-text-muted); background: var(--color-bg-tertiary);">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Content --}}
                <div class="p-6 space-y-4">
                    {{-- Personal Information --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Personal Info</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Full Name</span>
                                <span class="font-medium text-sm" style="color: var(--color-text-primary);">{{ $viewingRecord->staff->full_name ?? 'Unknown' }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Employee ID</span>
                                <span class="font-medium text-sm" style="color: var(--color-text-primary);">{{ $viewingRecord->staff->profile->employee_id ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Role</span>
                                <span class="font-medium text-sm" style="color: var(--color-text-primary);">{{ ucfirst(str_replace('_', ' ', $viewingRecord->staff->staffType->name ?? 'Unknown')) }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Department</span>
                                <span class="font-medium text-sm" style="color: var(--color-text-primary);">{{ $viewingRecord->staff->profile->department ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4" style="border-color: var(--color-border-base);"></div>

                    {{-- Period Information --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Period</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Name</span>
                                <span class="font-medium text-sm" style="color: var(--color-text-primary);">{{ $viewingRecord->payPeriod->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Dates</span>
                                <span class="font-medium text-sm text-right" style="color: var(--color-text-primary);">
                                    {{ $viewingRecord->payPeriod->period_start->format('M d') }}<br>- {{ $viewingRecord->payPeriod->period_end->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Pay Date</span>
                                <span class="font-medium text-sm" style="color: var(--color-text-primary);">{{ $viewingRecord->payPeriod->pay_date->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between items-start">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Status</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                      style="{{ $viewingRecord->status === 'draft' ? 'background-color: color-mix(in srgb, var(--color-text-muted) 20%, transparent); color: var(--color-text-muted);' : '' }}
                                             {{ $viewingRecord->status === 'calculated' ? 'background-color: var(--color-info-bg); color: var(--color-info);' : '' }}
                                             {{ $viewingRecord->status === 'approved' ? 'background-color: var(--color-warning-bg); color: var(--color-warning);' : '' }}
                                             {{ $viewingRecord->status === 'paid' ? 'background-color: var(--color-success-bg); color: var(--color-success);' : '' }}">
                                    {{ ucfirst($viewingRecord->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4" style="border-color: var(--color-border-base);"></div>

                    {{-- Working Hours --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Working Hours</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center p-2 rounded" style="background: var(--color-bg-tertiary);">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Regular</span>
                                @php
                                    $regMinutes = intval($viewingRecord->regular_hours * 60);
                                    $regHours = intdiv($regMinutes, 60);
                                    $regMins = $regMinutes % 60;
                                @endphp
                                <span class="font-bold text-sm" style="color: var(--color-text-primary);">{{ $regHours }}h {{ $regMins }}m</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded" style="background: var(--color-bg-tertiary);">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Overtime</span>
                                @php
                                    $otMinutes = intval($viewingRecord->overtime_hours * 60);
                                    $otHours = intdiv($otMinutes, 60);
                                    $otMins = $otMinutes % 60;
                                @endphp
                                <span class="font-bold text-sm" style="color: var(--color-warning);">{{ $otHours }}h {{ $otMins }}m</span>
                            </div>
                            <div class="flex justify-between items-center p-2 rounded border-2" style="background: color-mix(in srgb, var(--color-primary) 5%, transparent); border-color: var(--color-primary);">
                                <span style="color: var(--color-text-secondary);" class="text-xs font-semibold">Total</span>
                                @php
                                    $totalMinutes = intval(($viewingRecord->regular_hours + $viewingRecord->overtime_hours) * 60);
                                    $totalHrs = intdiv($totalMinutes, 60);
                                    $totalMns = $totalMinutes % 60;
                                @endphp
                                <span class="font-bold text-sm" style="color: var(--color-primary);">{{ $totalHrs }}h {{ $totalMns }}m</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4" style="border-color: var(--color-border-base);"></div>

                    {{-- Pay Information --}}
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color: var(--color-text-secondary);">Pay Information</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Hourly Rate</span>
                                <span class="font-bold text-sm" style="color: var(--color-text-primary);">£{{ number_format($viewingRecord->hourly_rate, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Gross Pay</span>
                                <span class="font-bold text-sm" style="color: var(--color-success);">£{{ number_format($viewingRecord->gross_pay, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span style="color: var(--color-text-secondary);" class="text-xs">Deductions</span>
                                <span class="font-bold text-sm" style="color: var(--color-warning);">£{{ number_format($viewingRecord->deductions, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-4" style="border-color: var(--color-border-base);"></div>

                    {{-- Net Pay (Highlight) --}}
                    <div class="p-3 rounded-lg border-2 text-center" style="background: color-mix(in srgb, var(--color-primary) 8%, transparent); border-color: var(--color-primary);">
                        <p class="text-xs mb-1" style="color: var(--color-text-secondary);">Net Pay</p>
                        <p class="text-2xl font-bold" style="color: var(--color-primary);">£{{ number_format($viewingRecord->net_pay, 2) }}</p>
                    </div>

                    {{-- Notes --}}
                    @if($viewingRecord->notes)
                        <div class="border-t pt-4" style="border-color: var(--color-border-base);">
                            <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color: var(--color-text-secondary);">Notes</p>
                            <p class="text-xs p-2 rounded" style="background: var(--color-bg-tertiary); color: var(--color-text-primary);">
                                {{ $viewingRecord->notes }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="border-t sticky bottom-0 px-6 py-3 flex gap-2" style="background: var(--color-bg-secondary); border-color: var(--color-border-base);">
                    <button wire:click="closeDetailModal"
                            class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all border"
                            style="background: transparent; border-color: var(--color-border-base); color: var(--color-text-secondary);">
                        Close
                    </button>
                    <button wire:click="openEditModal('{{ $viewingRecord->id }}')"
                            class="flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all inline-flex items-center justify-center gap-1"
                            style="background: var(--color-warning); color: white;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

