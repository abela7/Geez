<div class="min-h-screen" style="background-color: var(--color-bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                        Pay Periods
                    </h1>
                    <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">
                        Manage payroll periods and generate payroll for your staff
                    </p>
                </div>
                @if(!$showCreateForm)
                <button wire:click="startCreate"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white btn"
                        style="background-color: var(--color-primary); transition: var(--transition-base);"
                        onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                        onmouseout="this.style.backgroundColor='var(--color-primary)'">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Period
                </button>
                @endif
            </div>
        </div>

        @if($showCreateForm)
            <!-- Create/Edit Form -->
            <div class="shadow rounded-lg mb-6" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
                <div class="px-6 py-4" style="border-bottom: 1px solid var(--color-border-base);">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-medium" style="color: var(--color-text-primary);">
                            {{ $isEditing ? 'Edit Pay Period' : 'New Pay Period' }}
                        </h2>
                        <button wire:click="cancelForm" 
                                class="transition-colors"
                                style="color: var(--color-text-muted);"
                                onmouseover="this.style.color='var(--color-text-secondary)'"
                                onmouseout="this.style.color='var(--color-text-muted)'">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <form wire:submit="save" class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Period Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Period Name
                            </label>
                            <input type="text" 
                                   wire:model="name" 
                                   id="name"
                                   class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                   style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                   onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                   onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                            @error('name') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Period Type -->
                        <div>
                            <label for="period_type" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Period Type
                            </label>
                            <select wire:model="period_type" 
                                    id="period_type"
                                    class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                    style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                    onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                    onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                <option value="weekly">Weekly</option>
                                <option value="biweekly">Bi-weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                            @error('period_type') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Period Start -->
                        <div>
                            <label for="period_start" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Period Start
                            </label>
                            <input type="date" 
                                   wire:model="period_start" 
                                   id="period_start"
                                   class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                   style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                   onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                   onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                            @error('period_start') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Period End -->
                        <div>
                            <label for="period_end" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Period End
                            </label>
                            <input type="date" 
                                   wire:model="period_end" 
                                   id="period_end"
                                   class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                   style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                   onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                   onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                            @error('period_end') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Pay Date -->
                        <div>
                            <label for="pay_date" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Pay Date
                            </label>
                            <input type="date" 
                                   wire:model="pay_date" 
                                   id="pay_date"
                                   class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                   style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                   onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                   onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                            @error('pay_date') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Payroll Setting -->
                        <div>
                            <label for="payroll_setting_id" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Payroll Setting
                            </label>
                            <select wire:model="payroll_setting_id" 
                                    id="payroll_setting_id"
                                    class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                    style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                    onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                    onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                <option value="">Select Setting</option>
                                @foreach($payrollSettings as $setting)
                                    <option value="{{ $setting->id }}">{{ $setting->name }}</option>
                                @endforeach
                            </select>
                            @error('payroll_setting_id') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                Notes (Optional)
                            </label>
                            <textarea wire:model="notes" 
                                      id="notes"
                                      rows="3"
                                      class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                      style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                      onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                      onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';"></textarea>
                            @error('notes') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                wire:click="cancelForm"
                                class="px-4 py-2 border rounded-md shadow-sm text-sm font-medium btn transition-colors"
                                style="background-color: var(--color-bg-tertiary); border-color: var(--color-border-base); color: var(--color-text-primary);"
                                onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white btn transition-colors"
                                style="background-color: var(--color-primary);"
                                onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                                onmouseout="this.style.backgroundColor='var(--color-primary)'">
                            {{ $isEditing ? 'Update' : 'Create' }}
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Filters and Search -->
        <div class="shadow rounded-lg mb-6" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <!-- Status Filter Tabs -->
                    <div class="flex space-x-1">
                        @foreach(['all' => 'All', 'open' => 'Open', 'processing' => 'Processing', 'closed' => 'Closed'] as $status => $label)
                            <button wire:click="setStatusFilter('{{ $status }}')"
                                    class="px-3 py-2 text-sm font-medium rounded-md transition-colors"
                                    style="{{ $statusFilter === $status ? 'background-color: var(--color-primary); color: white;' : 'color: var(--color-text-muted);' }}"
                                    onmouseover="if('{{ $statusFilter }}' !== '{{ $status }}') { this.style.color='var(--color-text-secondary)'; this.style.backgroundColor='var(--color-bg-tertiary)'; }"
                                    onmouseout="if('{{ $statusFilter }}' !== '{{ $status }}') { this.style.color='var(--color-text-muted)'; this.style.backgroundColor='transparent'; }">
                                {{ $label }}
                                @if(isset($statusCounts[$status]) && $statusCounts[$status] > 0)
                                    <span class="ml-1 rounded-full px-2 py-0.5 text-xs"
                                          style="{{ $statusFilter === $status ? 'background-color: rgba(255,255,255,0.2); color: white;' : 'background-color: var(--color-bg-tertiary); color: var(--color-text-primary);' }}">
                                        {{ $statusCounts[$status] }}
                                    </span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    <!-- Search -->
                    <div class="relative">
                        <input type="text" 
                               wire:model.live="search"
                               placeholder="Search periods..."
                               class="block w-full pl-10 pr-3 py-2 rounded-md leading-5 transition-colors"
                               style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                               onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                               onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Periods List -->
        <div class="shadow rounded-lg" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
            <div class="px-6 py-4" style="border-bottom: 1px solid var(--color-border-base);">
                <h2 class="text-lg font-medium" style="color: var(--color-text-primary);">
                    Pay Periods
                </h2>
            </div>
            
            @if($periods->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full" style="border-collapse: separate; border-spacing: 0;">
                        <thead style="background-color: var(--color-bg-tertiary);">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Period
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Dates
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Records
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Total Amount
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider" style="color: var(--color-text-secondary);">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody style="background-color: var(--color-bg-secondary);">
                            @foreach($periods as $period)
                                <tr class="transition-colors"
                                    style="border-bottom: 1px solid var(--color-border-base);"
                                    onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                    onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium" style="color: var(--color-text-primary);">
                                                {{ $period->name }}
                                            </div>
                                            <div class="text-sm" style="color: var(--color-text-secondary);">
                                                Pay Date: {{ $period->pay_date ? $period->pay_date->format('M d, Y') : 'Not set' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                        {{ $period->period_start->format('M d') }} - {{ $period->period_end->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                              style="{{ $period->status === 'open' ? 'background-color: var(--color-info-bg); color: var(--color-info);' : '' }}
                                                     {{ $period->status === 'processing' ? 'background-color: var(--color-warning-bg); color: var(--color-warning);' : '' }}
                                                     {{ $period->status === 'closed' ? 'background-color: var(--color-success-bg); color: var(--color-success);' : '' }}">
                                            {{ ucfirst($period->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                        @if($period->total_staff_count > 0)
                                            <a href="{{ route('admin.staff.payroll.periods.records', $period->id) }}" 
                                               class="inline-flex items-center gap-1 font-medium hover:underline transition-colors"
                                               style="color: var(--color-primary);">
                                                {{ $period->total_staff_count }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        @else
                                            {{ $period->total_staff_count ?? 0 }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm" style="color: var(--color-text-primary);">
                                        @if($period->total_net_pay)
                                            £{{ number_format($period->total_net_pay, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- Quick Access Button - Opens Side Modal -->
                                            <button wire:click="openModal('{{ $period->id }}')" 
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded transition-all"
                                                    style="background-color: var(--color-primary); color: white;"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                </svg>
                                                Actions
                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            
                                            <!-- Edit Button -->
                                            <button wire:click="startEdit('{{ $period->id }}')" 
                                                    type="button"
                                                    class="inline-flex items-center p-2 rounded transition-all"
                                                    style="background-color: var(--color-warning-bg); color: var(--color-warning);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                                                    title="Edit Period">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </button>
                                            
                                            <!-- Delete Button -->
                                            <button wire:click="deletePeriod('{{ $period->id }}')"
                                                    wire:confirm="Are you sure you want to delete this period? This action cannot be undone."
                                                    type="button"
                                                    class="inline-flex items-center p-2 rounded transition-all"
                                                    style="background-color: var(--color-error-bg); color: var(--color-error);"
                                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='var(--shadow-md)';"
                                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';"
                                                    title="Delete Period">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4" style="border-top: 1px solid var(--color-border-base);">
                    {{ $periods->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-text-muted);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-4 4v10m-4-10v10m8-10v10m-4-10H8m8 0h4"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium" style="color: var(--color-text-primary);">No pay periods</h3>
                    <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">
                        Get started by creating your first pay period.
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Side Modal for Actions -->
    @if($showModal && $selectedPeriod)
        @teleport('body')
            <div x-data="{ show: @entangle('showModal') }"
                 x-show="show"
                 @keydown.escape.window="$wire.closeModal()"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 overflow-hidden"
                 style="z-index: 99999;">
                
                <!-- Backdrop -->
                <div class="absolute inset-0" style="background-color: rgba(0, 0, 0, 0.7);" wire:click="closeModal"></div>
            
            <!-- Side Panel -->
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="transform translate-x-full"
                 x-transition:enter-end="transform translate-x-0"
                 x-transition:leave="transition ease-in duration-250"
                 x-transition:leave-start="transform translate-x-0"
                 x-transition:leave-end="transform translate-x-full"
                 class="absolute right-0 top-0 h-full w-full max-w-md shadow-2xl flex flex-col"
                 style="background-color: var(--color-bg-secondary);"
                 @click.stop>
        
                <!-- Header -->
                <div class="flex items-center justify-between p-6" style="border-bottom: 1px solid var(--color-border-base);">
                    <h2 class="text-xl font-bold" style="color: var(--color-text-primary);">Period Actions</h2>
                    <button wire:click="closeModal" class="transition-colors" style="color: var(--color-text-muted);" onmouseover="this.style.color='var(--color-text-primary)'" onmouseout="this.style.color='var(--color-text-muted)'">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Content -->
                <div class="flex-1 overflow-y-auto p-6 space-y-3">
                    <!-- Review Payroll -->
                    <a href="/admin/staff/payroll/periods/{{ $selectedPeriod }}/review"
                       class="flex items-center px-4 py-3 rounded-lg transition-colors"
                       style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);"
                       onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'; this.style.color='var(--color-primary)';"
                       onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'; this.style.color='var(--color-text-primary)';">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <div>
                            <div class="font-medium">Review Payroll</div>
                            <div class="text-xs" style="color: var(--color-text-secondary);">Review and approve records</div>
                        </div>
                    </a>
                    
                    <!-- Process Payment -->
                    <a href="/admin/staff/payroll/periods/{{ $selectedPeriod }}/payment"
               class="flex items-center px-4 py-3 rounded-lg transition-colors"
               style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);"
               onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'; this.style.color='var(--color-primary)';"
               onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'; this.style.color='var(--color-text-primary)';">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <div>
                    <div class="font-medium">Process Payment</div>
                    <div class="text-xs" style="color: var(--color-text-secondary);">Process staff payments</div>
                </div>
            </a>
            
            <!-- View Reports -->
                    <a href="/admin/staff/payroll/periods/{{ $selectedPeriod }}/reports"
               class="flex items-center px-4 py-3 rounded-lg transition-colors"
               style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);"
               onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'; this.style.color='var(--color-primary)';"
               onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'; this.style.color='var(--color-text-primary)';">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div>
                    <div class="font-medium">View Reports</div>
                    <div class="text-xs" style="color: var(--color-text-secondary);">Generate payslips & summaries</div>
                </div>
            </a>
            
            <!-- View Records -->
                    <a href="/admin/staff/payroll/periods/{{ $selectedPeriod }}/records"
               class="flex items-center px-4 py-3 rounded-lg transition-colors"
               style="background-color: var(--color-bg-tertiary); color: var(--color-text-primary);"
               onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'; this.style.color='var(--color-primary)';"
               onmouseout="this.style.backgroundColor='var(--color-bg-tertiary)'; this.style.color='var(--color-text-primary)';">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <div>
                    <div class="font-medium">View All Records</div>
                    <div class="text-xs" style="color: var(--color-text-secondary);">View all payroll records</div>
                </div>
                    </a>
                </div>
            </div>
        </div>
        @endteleport
    @endif
</div>
