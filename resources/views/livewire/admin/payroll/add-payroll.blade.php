<div>
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">Add Payroll</h1>
                <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">
                    Generate payroll by manually selecting staff and their attendance records
                </p>
            </div>
        </div>
    </div>

    <!-- Step 1: Period Selection -->
    <div class="mb-6 rounded-lg shadow-md p-6" style="background-color: var(--color-bg-secondary);">
        <h2 class="text-lg font-semibold mb-4" style="color: var(--color-text-primary);">Step 1: Select Pay Period</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <label for="period" class="block text-sm font-medium mb-2" style="color: var(--color-text-primary);">
                    Pay Period
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
            </div>
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
                        @if($selectedPeriod->total_staff_count > 0)
                            <a href="{{ route('admin.staff.payroll.periods.records', $selectedPeriod->id) }}" 
                               class="ml-2 font-medium inline-flex items-center gap-1 hover:underline transition-colors"
                               style="color: var(--color-primary);">
                                {{ $selectedPeriod->total_staff_count }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>
                        @else
                            <span class="ml-2 font-medium" style="color: var(--color-text-primary);">{{ $selectedPeriod->total_staff_count ?? 0 }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($selectedPeriodId)
        <!-- Step 2: Staff Selection -->
        <div class="mb-6 rounded-lg shadow-md p-6" style="background-color: var(--color-bg-secondary);">
            <h2 class="text-lg font-semibold mb-4" style="color: var(--color-text-primary);">Step 2: Select Staff Member</h2>
            
            @if(!$selectedStaffId)
                <!-- Search Staff -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="staffSearchTerm"
                               placeholder="Search staff by name or email..."
                               class="w-full rounded-md shadow-sm pl-10 pr-4 py-2 transition-colors"
                               style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                        <svg class="absolute left-3 top-2.5 w-5 h-5" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                <!-- Staff List -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($staffList as $staff)
                        <div wire:click="selectStaff('{{ $staff->id }}')" 
                             class="p-4 rounded-lg cursor-pointer transition-all"
                             style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base);"
                             onmouseover="this.style.borderColor='var(--color-primary)'; this.style.backgroundColor='var(--color-surface-card-hover)';"
                             onmouseout="this.style.borderColor='var(--color-border-base)'; this.style.backgroundColor='var(--color-bg-tertiary)';">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-medium" style="color: var(--color-text-primary);">{{ $staff->name }}</h3>
                                    <p class="text-sm mt-1" style="color: var(--color-text-secondary);">{{ $staff->staff_type }}</p>
                                    @if($staff->employee_id)
                                        <p class="text-xs mt-1" style="color: var(--color-text-muted);">ID: {{ $staff->employee_id }}</p>
                                    @endif
                                    <p class="text-sm mt-2 font-medium" style="color: var(--color-primary);">£{{ number_format($staff->hourly_rate, 2) }}/hr</p>
                                </div>
                                @if($staff->has_payroll)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                          style="background-color: var(--color-success-bg); color: var(--color-success);">
                                        Generated
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8">
                            <p style="color: var(--color-text-muted);">No staff found</p>
                        </div>
                    @endforelse
                </div>
            @else
                <!-- Selected Staff -->
                <div class="p-4 rounded-lg" style="background-color: var(--color-bg-tertiary); border: 2px solid var(--color-primary);">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-medium" style="color: var(--color-text-primary);">{{ $selectedStaff->name }}</h3>
                            <p class="text-sm mt-1" style="color: var(--color-text-secondary);">{{ $selectedStaff->staff_type }} | £{{ number_format($selectedStaff->hourly_rate, 2) }}/hr</p>
                        </div>
                        <button wire:click="clearStaffSelection" 
                                type="button"
                                class="px-3 py-1 rounded-md text-sm font-medium transition-colors"
                                style="background-color: var(--color-bg-primary); color: var(--color-text-primary); border: 1px solid var(--color-border-base);">
                            Change Staff
                        </button>
                    </div>
                </div>
            @endif
        </div>

        @if($selectedStaffId)
            <!-- Step 3: Attendance Selection -->
            <div class="mb-6 rounded-lg shadow-md p-6" style="background-color: var(--color-bg-secondary);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--color-text-primary);">Step 3: Select Attendance Records</h2>
                
                @if($attendanceRecords->isEmpty())
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p style="color: var(--color-text-muted);">No attendance records found for this period</p>
                    </div>
                @else
                    <div class="rounded-lg overflow-hidden" style="background-color: var(--color-bg-tertiary);">
                        <table class="w-full">
                            <thead style="background-color: var(--color-bg-secondary); position: sticky; top: 0; z-index: 10;">
                                <tr style="border-bottom: 1px solid var(--color-border-base);">
                                    <th class="px-4 py-3 text-left">
                                        <input type="checkbox" 
                                               wire:model.live="selectAllAttendance"
                                               class="rounded transition-colors"
                                               style="border-color: var(--color-border-base);">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Clock In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Clock Out</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Hours Worked</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendanceRecords as $attendance)
                                    <tr class="transition-colors"
                                        style="border-bottom: 1px solid var(--color-border-base);"
                                        onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                        onmouseout="this.style.backgroundColor='transparent'">
                                        <td class="px-4 py-4">
                                            <input type="checkbox" 
                                                   wire:click="toggleAttendanceSelection('{{ $attendance->id }}')"
                                                   @checked(in_array($attendance->id, $selectedAttendanceIds))
                                                   class="rounded transition-colors"
                                                   style="border-color: var(--color-border-base);">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" style="color: var(--color-text-primary);">
                                            {{ \Carbon\Carbon::parse($attendance->clock_in)->format('D, M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                            {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                            {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium" style="color: var(--color-text-primary);">
                                            @php
                                                $totalMinutes = intval($attendance->net_hours_worked * 60);
                                                $hours = intdiv($totalMinutes, 60);
                                                $minutes = $totalMinutes % 60;
                                            @endphp
                                            {{ $hours }}h {{ $minutes }}m
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                                  style="background-color: var(--color-success-bg); color: var(--color-success);">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Manual Hours Override -->
                    <div class="mt-4 p-4 rounded-md" style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base);">
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   wire:model.live="manualHours"
                                   wire:click="$set('manualHours', {{ $manualHours ? 'null' : '0' }})"
                                   class="rounded transition-colors mr-2"
                                   style="border-color: var(--color-border-base);">
                            <span class="text-sm font-medium" style="color: var(--color-text-primary);">Override with manual hours</span>
                        </label>
                        @if($manualHours !== null)
                            <div class="mt-3">
                                <input type="number" 
                                       wire:model.live="manualHours"
                                       step="0.01"
                                       min="0"
                                       placeholder="Enter hours (e.g., 8.5)"
                                       class="w-full md:w-64 rounded-md shadow-sm px-3 py-2"
                                       style="background-color: var(--color-bg-primary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Notes -->
                <div class="mt-4">
                    <label class="block text-sm font-medium mb-2" style="color: var(--color-text-primary);">
                        Notes (Optional)
                    </label>
                    <textarea wire:model="notes"
                              rows="2"
                              placeholder="Add any notes about this payroll..."
                              class="w-full rounded-md shadow-sm px-3 py-2"
                              style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"></textarea>
                </div>
            </div>

            <!-- Step 4: Summary & Generate -->
            <div class="rounded-lg shadow-md p-6" style="background-color: var(--color-bg-secondary); border-left: 4px solid var(--color-primary);">
                <h2 class="text-lg font-semibold mb-4" style="color: var(--color-text-primary);">Summary</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <p class="text-sm" style="color: var(--color-text-secondary);">Total Hours</p>
                        <p class="text-2xl font-bold mt-1" style="color: var(--color-text-primary);">
                            @php
                                $totalMinutes = intval($calculatedTotals['total_hours'] * 60);
                                $hours = intdiv($totalMinutes, 60);
                                $minutes = $totalMinutes % 60;
                            @endphp
                            {{ $hours }}h {{ $minutes }}m
                        </p>
                    </div>
                    <div>
                        <p class="text-sm" style="color: var(--color-text-secondary);">Hourly Rate</p>
                        <p class="text-2xl font-bold mt-1" style="color: var(--color-text-primary);">
                            £{{ number_format($calculatedTotals['hourly_rate'], 2) }}/hr
                        </p>
                    </div>
                    <div>
                        <p class="text-sm" style="color: var(--color-text-secondary);">Gross Pay</p>
                        <p class="text-2xl font-bold mt-1" style="color: var(--color-primary);">
                            £{{ number_format($calculatedTotals['gross_pay'], 2) }}
                        </p>
                    </div>
                </div>

                <button wire:click="generatePayroll" 
                        wire:loading.attr="disabled"
                        type="button"
                        class="w-full px-6 py-3 rounded-md font-medium text-lg transition-all flex items-center justify-center"
                        style="background-color: var(--color-success); color: white;"
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)';"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span wire:loading.remove>Generate Payroll</span>
                    <span wire:loading>Generating...</span>
                </button>
            </div>
        @endif
    @endif

    <!-- Create Period Modal -->
    @if($showCreatePeriodModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" 
             style="background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(4px);"
             wire:click.self="closeCreatePeriodModal">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="rounded-lg shadow-xl max-w-md w-full p-6" 
                     style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base);">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold" style="color: var(--color-text-primary);">Create Pay Period</h3>
                        <button wire:click="closeCreatePeriodModal" 
                                class="rounded-full p-1 hover:bg-opacity-10 transition-colors"
                                style="color: var(--color-text-secondary);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="createPeriod" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">Period Name</label>
                            <input type="text" 
                                   wire:model="newPeriodName"
                                   placeholder="e.g., November Week 1"
                                   class="w-full rounded-md shadow-sm px-3 py-2"
                                   style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                            @error('newPeriodName') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">Start Date</label>
                                <input type="date" 
                                       wire:model="newPeriodStart"
                                       class="w-full rounded-md shadow-sm px-3 py-2"
                                       style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                                @error('newPeriodStart') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">End Date</label>
                                <input type="date" 
                                       wire:model="newPeriodEnd"
                                       class="w-full rounded-md shadow-sm px-3 py-2"
                                       style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                                @error('newPeriodEnd') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" style="color: var(--color-text-primary);">Pay Date</label>
                            <input type="date" 
                                   wire:model="newPayDate"
                                   class="w-full rounded-md shadow-sm px-3 py-2"
                                   style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);">
                            @error('newPayDate') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

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
