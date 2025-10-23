<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Add Payroll</h1>
                <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">
                    Generate payroll for individual staff members based on their recorded hours
                </p>
            </div>
        </div>
    </div>

    <!-- Period Selection & Actions Card -->
    <div class="mb-6 rounded-lg shadow-md p-6" style="background-color: var(--color-bg-secondary);">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Period Selection -->
            <div class="md:col-span-2">
                <label for="period" class="block text-sm font-medium mb-2" style="color: var(--color-text-primary);">
                    Select Pay Period
                </label>
                <select wire:model.live="selectedPeriodId" 
                        id="period"
                        class="w-full rounded-md shadow-sm transition-colors"
                        style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary); padding: 0.5rem 1rem;">
                    <option value="">-- Select a Period --</option>
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}">
                            {{ $period->name }} ({{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }})
                        </option>
                    @endforeach
                </select>
                @if($periods->isEmpty())
                    <p class="mt-2 text-sm" style="color: var(--color-text-secondary);">
                        No open periods available. Create one to get started.
                    </p>
                @endif
            </div>

            <!-- Create New Period Button -->
            <div class="flex items-end">
                <button wire:click="openCreatePeriodModal" 
                        type="button"
                        class="w-full px-4 py-2 rounded-md font-medium transition-all"
                        style="background-color: var(--color-primary); color: white;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    New Period
                </button>
            </div>
        </div>

        <!-- Period Info -->
        @if($selectedPeriod)
            <div class="mt-4 p-4 rounded-md" style="background-color: var(--color-bg-tertiary); border-left: 4px solid var(--color-primary);">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span style="color: var(--color-text-secondary);">Period:</span>
                        <span class="ml-2 font-medium" style="color: var(--color-text-primary);">{{ $selectedPeriod->period_start->format('M d') }} - {{ $selectedPeriod->period_end->format('M d, Y') }}</span>
                    </div>
                    <div>
                        <span style="color: var(--color-text-secondary);">Pay Date:</span>
                        <span class="ml-2 font-medium" style="color: var(--color-text-primary);">{{ $selectedPeriod->pay_date ? $selectedPeriod->pay_date->format('M d, Y') : 'Not set' }}</span>
                    </div>
                    <div>
                        <span style="color: var(--color-text-secondary);">Status:</span>
                        <span class="ml-2 font-medium" style="color: var(--color-info);">{{ ucfirst($selectedPeriod->status) }}</span>
                    </div>
                    <div>
                        <span style="color: var(--color-text-secondary);">Records:</span>
                        <span class="ml-2 font-medium" style="color: var(--color-text-primary);">{{ $selectedPeriod->total_staff_count ?? 0 }}</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($selectedPeriodId)
        <!-- Search & Actions Bar -->
        <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <!-- Search -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchTerm"
                           placeholder="Search staff by name or email..."
                           class="w-full rounded-md shadow-sm pl-10 pr-4 py-2 transition-colors"
                           style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                    <svg class="absolute left-3 top-2.5 w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Generate Button -->
            <div class="flex items-center gap-3">
                <span class="text-sm" style="color: var(--color-text-secondary);">
                    {{ count($selectedStaff) }} selected
                </span>
                <button wire:click="generatePayroll" 
                        wire:loading.attr="disabled"
                        type="button"
                        class="px-6 py-2 rounded-md font-medium transition-all flex items-center"
                        style="background-color: var(--color-success); color: white;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span wire:loading.remove>Generate Payroll</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>

        <!-- Staff List Table -->
        <div class="rounded-lg shadow-md overflow-hidden" style="background-color: var(--color-bg-secondary);">
            @if($staffList->isEmpty())
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="text-lg font-medium mb-2" style="color: var(--color-text-primary);">No Staff Found</h3>
                    <p style="color: var(--color-text-secondary);">
                        @if($searchTerm)
                            No staff members match your search criteria.
                        @else
                            No active staff members found for this period.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead style="background-color: var(--color-bg-tertiary); position: sticky; top: 0; z-index: 10;">
                            <tr style="border-bottom: 1px solid var(--color-border-base);">
                                <th class="px-4 py-3 text-left">
                                    <input type="checkbox" 
                                           wire:model.live="selectAll"
                                           class="rounded transition-colors"
                                           style="border-color: var(--color-border-base);">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Staff Member
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Role
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Hours
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Rate
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Gross Pay
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($staffList as $staff)
                                <tr class="transition-colors"
                                    style="border-bottom: 1px solid var(--color-border-base);"
                                    onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                    onmouseout="this.style.backgroundColor='transparent'">
                                    <td class="px-4 py-4">
                                        @if($staff->has_payroll)
                                            <span class="text-sm" style="color: var(--color-text-muted);" title="Already has payroll">✓</span>
                                        @elseif(!$staff->has_profile)
                                            <span class="text-sm" style="color: var(--color-text-muted);" title="No profile">-</span>
                                        @else
                                            <input type="checkbox" 
                                                   wire:click="toggleStaffSelection('{{ $staff->id }}')"
                                                   @checked(in_array($staff->id, $selectedStaff))
                                                   class="rounded transition-colors"
                                                   style="border-color: var(--color-border-base);">
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium" style="color: var(--color-text-primary);">
                                                {{ $staff->name }}
                                            </div>
                                            @if($staff->employee_id)
                                                <div class="text-xs" style="color: var(--color-text-muted);">
                                                    ID: {{ $staff->employee_id }}
                                                </div>
                                            @else
                                                <div class="text-xs" style="color: var(--color-error);">
                                                    No Employee ID
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                        @if($staff->staff_type)
                                            {{ $staff->staff_type }}
                                        @else
                                            <span style="color: var(--color-text-muted);">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right" style="color: var(--color-text-primary);">
                                        {{ number_format($staff->total_hours, 2) }}h
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right" style="color: var(--color-text-primary);">
                                        £{{ number_format($staff->hourly_rate, 2) }}/hr
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium" style="color: var(--color-text-primary);">
                                        £{{ number_format($staff->gross_pay, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($staff->has_payroll)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: var(--color-success-bg); color: var(--color-success);">
                                                Generated
                                            </span>
                                        @elseif(!$staff->has_profile)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: var(--color-bg-tertiary); color: var(--color-text-muted);">
                                                No Profile
                                            </span>
                                        @elseif($staff->total_hours == 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: var(--color-warning-bg); color: var(--color-warning);">
                                                No Hours
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: var(--color-info-bg); color: var(--color-info);">
                                                Ready
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="px-6 py-4 border-t" style="border-color: var(--color-border-base); background-color: var(--color-bg-tertiary);">
                    <div class="flex justify-between items-center">
                        <span class="text-sm" style="color: var(--color-text-secondary);">
                            Total Staff: {{ $staffList->count() }} | 
                            Ready: {{ $staffList->where('has_profile', true)->where('has_payroll', false)->count() }} |
                            Already Generated: {{ $staffList->where('has_payroll', true)->count() }}
                        </span>
                        <span class="text-sm font-medium" style="color: var(--color-text-primary);">
                            Total Payroll: £{{ number_format($staffList->sum('gross_pay'), 2) }}
                        </span>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <!-- Create Period Modal -->
    @if($showCreatePeriodModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"
             wire:click.self="closeCreatePeriodModal">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="rounded-lg shadow-xl max-w-md w-full p-6" 
                     style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold" style="color: var(--color-text-primary);">
                            Create Pay Period
                        </h3>
                        <button wire:click="closeCreatePeriodModal" 
                                class="rounded-full p-1 hover:bg-opacity-10 transition-colors"
                                style="color: var(--color-text-secondary);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <form wire:submit.prevent="createPeriod" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">
                                Period Name
                            </label>
                            <input type="text" 
                                   wire:model="newPeriodName"
                                   placeholder="e.g., November Week 1"
                                   class="w-full rounded-md shadow-sm px-3 py-2"
                                   style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                            @error('newPeriodName') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">
                                    Start Date
                                </label>
                                <input type="date" 
                                       wire:model="newPeriodStart"
                                       class="w-full rounded-md shadow-sm px-3 py-2"
                                       style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                                @error('newPeriodStart') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">
                                    End Date
                                </label>
                                <input type="date" 
                                       wire:model="newPeriodEnd"
                                       class="w-full rounded-md shadow-sm px-3 py-2"
                                       style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                                @error('newPeriodEnd') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">
                                Pay Date
                            </label>
                            <input type="date" 
                                   wire:model="newPayDate"
                                   class="w-full rounded-md shadow-sm px-3 py-2"
                                   style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                            @error('newPayDate') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" 
                                    wire:click="closeCreatePeriodModal"
                                    class="px-4 py-2 rounded-md font-medium transition-colors"
                                    style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);">
                                Cancel
                            </button>
                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 rounded-md font-medium transition-colors"
                                    style="background-color: var(--color-primary); color: white;">
                                <span wire:loading.remove>Create Period</span>
                                <span wire:loading>Creating...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

