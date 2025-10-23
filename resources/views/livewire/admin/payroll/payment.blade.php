<div class="min-h-screen" style="background-color: var(--color-bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <a href="{{ route('admin.staff.payroll.periods') }}" 
                           class="inline-flex items-center justify-center w-10 h-10 rounded-lg transition-all"
                           style="background-color: var(--color-bg-secondary); color: var(--color-text-muted); border: 1px solid var(--color-border-base);"
                           onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'; this.style.color='var(--color-primary)';"
                           onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'; this.style.color='var(--color-text-muted)';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-3xl font-bold" style="color: var(--color-text-primary);">Process Payments</h1>
                            <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                                {{ $period->name }} • {{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <button wire:click="openProcessModal" 
                            type="button"
                            class="inline-flex items-center px-6 py-3 rounded-lg text-sm font-semibold transition-all gap-2 shadow-md"
                            style="background-color: var(--color-primary); color: white;"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(205, 175, 86, 0.3)';"
                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-md)';">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Process Payment ({{ count($selectedRecords) }})
                    </button>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Approved Records Card -->
            <div class="rounded-lg p-6 transition-all"
                 style="background: linear-gradient(135deg, #301934 0%, #4D4052 100%); border: 1px solid var(--color-border-base); box-shadow: var(--shadow-md);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium mb-2" style="color: rgba(255, 255, 255, 0.7);">APPROVED RECORDS</p>
                        <p class="text-4xl font-bold" style="color: white;">{{ $approvedRecords->count() }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: rgba(205, 175, 86, 0.2);">
                        <svg class="w-7 h-7" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Selected Records Card -->
            <div class="rounded-lg p-6 transition-all"
                 style="background: linear-gradient(135deg, #4D4052 0%, #6B5B73 100%); border: 1px solid var(--color-border-base); box-shadow: var(--shadow-md);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium mb-2" style="color: rgba(255, 255, 255, 0.7);">SELECTED FOR PAYMENT</p>
                        <p class="text-4xl font-bold" style="color: var(--color-primary);">{{ count($selectedRecords) }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: rgba(205, 175, 86, 0.2);">
                        <svg class="w-7 h-7" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Amount Card -->
            <div class="rounded-lg p-6 transition-all"
                 style="background: linear-gradient(135deg, #2D7A3E 0%, #3D9A52 100%); border: 1px solid var(--color-border-base); box-shadow: var(--shadow-md);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium mb-2" style="color: rgba(255, 255, 255, 0.7);">TOTAL AMOUNT</p>
                        <p class="text-4xl font-bold" style="color: white;">£{{ number_format($totalSelectedAmount, 2) }}</p>
                    </div>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center" style="background-color: rgba(255, 255, 255, 0.2);">
                        <svg class="w-7 h-7" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="mb-6">
            <div class="rounded-lg p-6" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
                <label class="block text-sm font-semibold mb-3" style="color: var(--color-text-primary);">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search Staff Member
                </label>
                <div class="relative max-w-md">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Type staff name to filter..."
                           class="w-full rounded-lg shadow-sm pl-11 pr-4 py-3 text-sm transition-all"
                           style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-border-base); color: var(--color-text-primary);"
                           onfocus="this.style.borderColor='var(--color-primary)'"
                           onblur="this.style.borderColor='var(--color-border-base)'">
                    <svg class="absolute left-4 top-3.5 w-4 h-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Staff Records Table -->
        <div class="rounded-lg shadow-lg overflow-hidden" style="background-color: var(--color-bg-secondary);">
            @if($approvedRecords->isEmpty())
                <div class="p-16 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-6" style="background-color: var(--color-bg-tertiary);">
                        <svg class="w-10 h-10" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--color-text-primary);">No Approved Records Found</h3>
                    <p class="text-sm mb-6" style="color: var(--color-text-secondary);">
                        @if($search)
                            No records match your search criteria. Try adjusting your search terms.
                        @else
                            No payroll records have been approved yet. Please approve records in the Review page first.
                        @endif
                    </p>
                    <a href="{{ route('admin.staff.payroll.periods') }}/{{ $period->id }}/review"
                       class="inline-flex items-center px-5 py-2.5 rounded-lg text-sm font-medium transition-all"
                       style="background-color: var(--color-primary); color: white;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Go to Review Page
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y" style="border-color: var(--color-border-base);">
                        <thead style="background-color: var(--color-bg-tertiary);">
                            <tr>
                                <th class="px-6 py-4 text-left">
                                    <input type="checkbox" 
                                           wire:model.live="selectAll"
                                           class="w-4 h-4 rounded transition-colors cursor-pointer"
                                           style="border-color: var(--color-border-base); color: var(--color-primary);">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Staff Member
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Employee ID
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Hours Worked
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Net Pay
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Quick Action
                                </th>
                            </tr>
                        </thead>
                        <tbody style="background-color: var(--color-bg-secondary);">
                            @foreach($approvedRecords as $record)
                                <tr class="transition-all"
                                    style="border-bottom: 1px solid var(--color-border-base);"
                                    onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                    onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'">
                                    <td class="px-6 py-5">
                                        <input type="checkbox" 
                                               wire:click="toggleRecordSelection('{{ $record->id }}')"
                                               @checked(in_array($record->id, $selectedRecords))
                                               class="w-4 h-4 rounded transition-colors cursor-pointer"
                                               style="border-color: var(--color-border-base); color: var(--color-primary);">
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center font-bold text-sm" 
                                                 style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); color: white;">
                                                {{ substr($record->staff->full_name ?? 'U', 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold" style="color: var(--color-text-primary);">
                                                    {{ $record->staff->full_name ?? 'Unknown' }}
                                                </div>
                                                <div class="text-xs" style="color: var(--color-text-secondary);">
                                                    {{ $record->staff->staffType->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="text-sm font-medium px-2.5 py-1 rounded" 
                                              style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);">
                                            {{ $record->staff->profile->employee_id ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        @php
                                            $totalMinutes = intval(($record->regular_hours + $record->overtime_hours) * 60);
                                            $hours = intdiv($totalMinutes, 60);
                                            $minutes = $totalMinutes % 60;
                                        @endphp
                                        <span class="text-sm font-semibold" style="color: var(--color-text-primary);">
                                            {{ $hours }}h {{ $minutes }}m
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <span class="text-lg font-bold" style="color: var(--color-success);">
                                            £{{ number_format($record->net_pay, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-center">
                                        <button wire:click="markAsPaid('{{ $record->id }}')"
                                                wire:confirm="Are you sure you want to mark this as paid?"
                                                class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold transition-all gap-1.5"
                                                style="background-color: var(--color-success); color: white;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Mark Paid
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Process Payment Modal -->
    @if($showProcessModal)
        @teleport('body')
            <div class="fixed inset-0 z-50 overflow-y-auto" 
                 style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(8px);">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="rounded-xl shadow-2xl max-w-2xl w-full p-8 relative" 
                         style="background: linear-gradient(135deg, var(--color-bg-secondary) 0%, var(--color-bg-tertiary) 100%); border: 2px solid var(--color-primary);">
                        <!-- Modal Header -->
                        <div class="flex items-center justify-between mb-6 pb-4" style="border-bottom: 2px solid var(--color-border-base);">
                            <div>
                                <h3 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                                    <svg class="w-6 h-6 inline mr-2" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Process Payments
                                </h3>
                                <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Complete payment details for selected staff</p>
                            </div>
                            <button wire:click="closeProcessModal" 
                                    class="rounded-full p-2 transition-all"
                                    style="background-color: var(--color-bg-tertiary); color: var(--color-text-muted);"
                                    onmouseover="this.style.backgroundColor='var(--color-error-bg)'; this.style.color='var(--color-error)';"
                                    onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'; this.style.color='var(--color-text-muted)';">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <form wire:submit.prevent="processPayment">
                            <!-- Summary Banner -->
                            <div class="mb-6 p-5 rounded-lg" 
                                 style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); box-shadow: var(--shadow-md);">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <div>
                                            <span style="color: rgba(255, 255, 255, 0.8);">Staff Selected:</span>
                                            <span class="ml-2 font-bold text-lg" style="color: white;">{{ count($selectedRecords) }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end">
                                        <svg class="w-5 h-5 mr-2" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div>
                                            <span style="color: rgba(255, 255, 255, 0.8);">Total:</span>
                                            <span class="ml-2 font-bold text-xl" style="color: white;">£{{ number_format($totalSelectedAmount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Payment Method -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text-primary);">
                                        Payment Method <span style="color: var(--color-error);">*</span>
                                    </label>
                                    <select wire:model="paymentMethod"
                                            class="w-full rounded-lg shadow-sm px-4 py-3 text-sm transition-all"
                                            style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-border-base); color: var(--color-text-primary);">
                                        <option value="bank_transfer">💳 Bank Transfer</option>
                                        <option value="cash">💵 Cash</option>
                                        <option value="check">📝 Cheque</option>
                                        <option value="mobile_money">📱 Mobile Money</option>
                                        <option value="other">⚙️ Other</option>
                                    </select>
                                    @error('paymentMethod') <p class="mt-1.5 text-xs font-medium" style="color: var(--color-error);">{{ $message }}</p> @enderror
                                </div>

                                <!-- Payment Date -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text-primary);">
                                        Payment Date <span style="color: var(--color-error);">*</span>
                                    </label>
                                    <input type="date" 
                                           wire:model="paymentDate"
                                           class="w-full rounded-lg shadow-sm px-4 py-3 text-sm transition-all"
                                           style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-border-base); color: var(--color-text-primary);">
                                    @error('paymentDate') <p class="mt-1.5 text-xs font-medium" style="color: var(--color-error);">{{ $message }}</p> @enderror
                                </div>

                                <!-- Transaction Reference -->
                                <div>
                                    <label class="block text-sm font-semibold mb-2" style="color: var(--color-text-primary);">
                                        Transaction Reference
                                    </label>
                                    <input type="text" 
                                           wire:model="transactionReference"
                                           placeholder="e.g., TXN-123456, Check #789"
                                           class="w-full rounded-lg shadow-sm px-4 py-3 text-sm transition-all"
                                           style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-border-base); color: var(--color-text-primary);">
                                    @error('transactionReference') <p class="mt-1.5 text-xs font-medium" style="color: var(--color-error);">{{ $message }}</p> @enderror
                                </div>

                                <!-- Bank Name (conditional) -->
                                @if($paymentMethod === 'bank_transfer')
                                    <div>
                                        <label class="block text-sm font-semibold mb-2" style="color: var(--color-text-primary);">
                                            Bank Name
                                        </label>
                                        <input type="text" 
                                               wire:model="bankName"
                                               placeholder="e.g., Barclays, HSBC"
                                               class="w-full rounded-lg shadow-sm px-4 py-3 text-sm transition-all"
                                               style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-border-base); color: var(--color-text-primary);">
                                        @error('bankName') <p class="mt-1.5 text-xs font-medium" style="color: var(--color-error);">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                            </div>

                            <!-- Payment Notes -->
                            <div class="mt-5">
                                <label class="block text-sm font-semibold mb-2" style="color: var(--color-text-primary);">
                                    Payment Notes (Optional)
                                </label>
                                <textarea wire:model="paymentNotes" 
                                          rows="3"
                                          placeholder="Add any notes about this payment batch..."
                                          class="w-full rounded-lg shadow-sm px-4 py-3 text-sm transition-all"
                                          style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-border-base); color: var(--color-text-primary);"></textarea>
                                @error('paymentNotes') <p class="mt-1.5 text-xs font-medium" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <!-- Modal Footer -->
                            <div class="flex justify-end gap-4 mt-8 pt-6" style="border-top: 2px solid var(--color-border-base);">
                                <button type="button" 
                                        wire:click="closeProcessModal"
                                        class="px-6 py-3 rounded-lg text-sm font-semibold transition-all"
                                        style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary); border: 2px solid var(--color-border-base);"
                                        onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)';"
                                        onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)';">
                                    Cancel
                                </button>
                                <button type="submit"
                                        wire:loading.attr="disabled"
                                        class="px-8 py-3 rounded-lg text-sm font-bold transition-all inline-flex items-center gap-2 shadow-md"
                                        style="background-color: var(--color-primary); color: white;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 20px rgba(205, 175, 86, 0.3)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-md)';">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span wire:loading.remove>Process Payment</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endteleport
    @endif
</div>
