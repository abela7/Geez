<div class="min-h-screen" style="background-color: var(--color-bg-primary);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold" style="color: var(--color-text-primary);">
                        Payroll Settings
                    </h1>
                    <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">
                        Configure payroll calculation rules, overtime policies, and system defaults
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
                    New Setting
                </button>
                @endif
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="shadow rounded-lg mb-6" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
            <div style="border-bottom: 1px solid var(--color-border-base);">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button wire:click="switchTab('general')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                            style="{{ $activeTab === 'general' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'border-color: transparent; color: var(--color-text-muted);' }}"
                            onmouseover="if('{{ $activeTab }}' !== 'general') { this.style.color='var(--color-text-secondary)'; this.style.borderColor='var(--color-border-base)'; }"
                            onmouseout="if('{{ $activeTab }}' !== 'general') { this.style.color='var(--color-text-muted)'; this.style.borderColor='transparent'; }">
                        General Settings
                    </button>
                    <button wire:click="switchTab('templates')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                            style="{{ $activeTab === 'templates' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'border-color: transparent; color: var(--color-text-muted);' }}"
                            onmouseover="if('{{ $activeTab }}' !== 'templates') { this.style.color='var(--color-text-secondary)'; this.style.borderColor='var(--color-border-base)'; }"
                            onmouseout="if('{{ $activeTab }}' !== 'templates') { this.style.color='var(--color-text-muted)'; this.style.borderColor='transparent'; }">
                        Templates
                    </button>
                    <button wire:click="switchTab('tax')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                            style="{{ $activeTab === 'tax' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'border-color: transparent; color: var(--color-text-muted);' }}"
                            onmouseover="if('{{ $activeTab }}' !== 'tax') { this.style.color='var(--color-text-secondary)'; this.style.borderColor='var(--color-border-base)'; }"
                            onmouseout="if('{{ $activeTab }}' !== 'tax') { this.style.color='var(--color-text-muted)'; this.style.borderColor='transparent'; }">
                        Tax Brackets
                    </button>
                    <button wire:click="switchTab('deductions')" 
                            class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                            style="{{ $activeTab === 'deductions' ? 'border-color: var(--color-primary); color: var(--color-primary);' : 'border-color: transparent; color: var(--color-text-muted);' }}"
                            onmouseover="if('{{ $activeTab }}' !== 'deductions') { this.style.color='var(--color-text-secondary)'; this.style.borderColor='var(--color-border-base)'; }"
                            onmouseout="if('{{ $activeTab }}' !== 'deductions') { this.style.color='var(--color-text-muted)'; this.style.borderColor='transparent'; }">
                        Deduction Types
                    </button>
                </nav>
            </div>
        </div>

        <!-- Tab Content -->
        @if($activeTab === 'general')
            <!-- General Settings Tab -->
            <div class="shadow rounded-lg" style="background-color: var(--color-bg-secondary); box-shadow: var(--shadow-md);">
                @if($showCreateForm)
                    <!-- Create/Edit Form -->
                    <div class="px-6 py-4" style="border-bottom: 1px solid var(--color-border-base);">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-medium" style="color: var(--color-text-primary);">
                                {{ $isEditing ? 'Edit Settings' : 'New Settings' }}
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
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                    Setting Name
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

                            <!-- Pay Frequency -->
                            <div>
                                <label for="pay_frequency" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                    Pay Frequency
                                </label>
                                <select wire:model="pay_frequency" 
                                        id="pay_frequency"
                                        class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                        style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                        onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                        onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                    <option value="weekly">Weekly</option>
                                    <option value="biweekly">Bi-weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                                @error('pay_frequency') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <!-- Overtime Threshold -->
                            <div>
                                <label for="overtime_threshold_hours" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                    Overtime Threshold (hours)
                                </label>
                                <input type="number" 
                                       wire:model="overtime_threshold_hours" 
                                       id="overtime_threshold_hours"
                                       step="0.25"
                                       min="1"
                                       max="168"
                                       class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                       style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                       onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                       onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                @error('overtime_threshold_hours') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <!-- Overtime Multiplier -->
                            <div>
                                <label for="overtime_multiplier" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                    Overtime Multiplier
                                </label>
                                <input type="number" 
                                       wire:model="overtime_multiplier" 
                                       id="overtime_multiplier"
                                       step="0.25"
                                       min="1"
                                       max="5"
                                       class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                       style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                       onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                       onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                @error('overtime_multiplier') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <!-- Currency -->
                            <div>
                                <label for="currency_code" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                    Currency Code
                                </label>
                                <select wire:model="currency_code" 
                                        id="currency_code"
                                        class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                        style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                        onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                        onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                    <option value="USD">USD - US Dollar</option>
                                    <option value="EUR">EUR - Euro</option>
                                    <option value="GBP">GBP - British Pound</option>
                                    <option value="CAD">CAD - Canadian Dollar</option>
                                    <option value="ETB">ETB - Ethiopian Birr</option>
                                </select>
                                @error('currency_code') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>

                            <!-- Rounding Mode -->
                            <div>
                                <label for="rounding_mode" class="block text-sm font-medium" style="color: var(--color-text-primary);">
                                    Rounding Mode
                                </label>
                                <select wire:model="rounding_mode" 
                                        id="rounding_mode"
                                        class="mt-1 block w-full rounded-md shadow-sm transition-colors"
                                        style="background-color: var(--color-bg-secondary); border: 1px solid var(--color-border-base); color: var(--color-text-primary);"
                                        onfocus="this.style.borderColor='var(--color-primary)'; this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                        onblur="this.style.borderColor='var(--color-border-base)'; this.style.boxShadow='none';">
                                    <option value="up">Round Up</option>
                                    <option value="down">Round Down</option>
                                    <option value="nearest">Round to Nearest</option>
                                </select>
                                @error('rounding_mode') <p class="mt-1 text-sm" style="color: var(--color-error);">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Auto Calculate Tax -->
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   wire:model="auto_calculate_tax" 
                                   id="auto_calculate_tax"
                                   class="h-4 w-4 rounded transition-colors"
                                   style="color: var(--color-primary); border-color: var(--color-border-base);"
                                   onfocus="this.style.boxShadow='0 0 0 3px rgba(var(--color-primary-rgb), 0.1)';"
                                   onblur="this.style.boxShadow='none';">
                            <label for="auto_calculate_tax" class="ml-2 block text-sm" style="color: var(--color-text-primary);">
                                Automatically calculate taxes based on tax brackets
                            </label>
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
                @else
                    <!-- Settings List -->
                    <div class="px-6 py-4" style="border-bottom: 1px solid var(--color-border-base);">
                        <h2 class="text-lg font-medium" style="color: var(--color-text-primary);">
                            Current Settings
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($settings->count() > 0)
                            <div class="space-y-4">
                                @foreach($settings as $setting)
                                    <div class="rounded-lg p-4 transition-colors" 
                                         style="border: 1px solid var(--color-border-base); {{ $setting->is_default ? 'box-shadow: 0 0 0 2px var(--color-primary);' : '' }}"
                                         onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'"
                                         onmouseout="this.style.backgroundColor='var(--color-bg-secondary)'">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">
                                                        {{ $setting->name }}
                                                    </h3>
                                                    @if($setting->is_default)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                              style="background-color: var(--color-primary); color: white;">
                                                            Default
                                                        </span>
                                                    @endif
                                                    @if(!$setting->is_active)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                              style="background-color: var(--color-error-bg); color: var(--color-error);">
                                                            Inactive
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm" style="color: var(--color-text-secondary);">
                                                    <div>
                                                        <span class="font-medium">Frequency:</span> {{ ucfirst($setting->pay_frequency) }}
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">Overtime:</span> {{ $setting->overtime_threshold_hours }}h @ {{ $setting->overtime_multiplier }}x
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">Currency:</span> {{ $setting->currency_code }}
                                                    </div>
                                                    <div>
                                                        <span class="font-medium">Rounding:</span> {{ ucfirst($setting->rounding_mode) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if(!$setting->is_default)
                                                    <button wire:click="makeDefault('{{ $setting->id }}')"
                                                            wire:loading.attr="disabled"
                                                            class="text-sm font-medium transition-colors"
                                                            style="color: var(--color-primary);"
                                                            onmouseover="this.style.color='var(--color-secondary)'"
                                                            onmouseout="this.style.color='var(--color-primary)'">
                                                        <span wire:loading.remove wire:target="makeDefault('{{ $setting->id }}')">Make Default</span>
                                                        <span wire:loading wire:target="makeDefault('{{ $setting->id }}')">Setting...</span>
                                                    </button>
                                                @endif
                                                <button wire:click="toggleActive('{{ $setting->id }}')"
                                                        class="text-sm font-medium transition-colors"
                                                        style="color: var(--color-text-secondary);"
                                                        onmouseover="this.style.color='var(--color-text-primary)'"
                                                        onmouseout="this.style.color='var(--color-text-secondary)'">
                                                    {{ $setting->is_active ? 'Deactivate' : 'Activate' }}
                                                </button>
                                                <button wire:click="startEdit('{{ $setting->id }}')"
                                                        class="text-sm font-medium transition-colors"
                                                        style="color: var(--color-primary);"
                                                        onmouseover="this.style.color='var(--color-secondary)'"
                                                        onmouseout="this.style.color='var(--color-primary)'">
                                                    Edit
                                                </button>
                                                <button wire:click="confirmDelete('{{ $setting->id }}')"
                                                        wire:loading.attr="disabled"
                                                        onclick="console.log('Delete clicked for {{ $setting->id }}'); alert('Delete button clicked!');"
                                                        class="text-sm font-medium transition-colors"
                                                        style="color: var(--color-error);"
                                                        onmouseover="this.style.color='var(--color-error)'"
                                                        onmouseout="this.style.color='var(--color-error)'">
                                                    <span wire:loading.remove wire:target="confirmDelete('{{ $setting->id }}')">Delete</span>
                                                    <span wire:loading wire:target="confirmDelete('{{ $setting->id }}')">Loading...</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-text-muted);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium" style="color: var(--color-text-primary);">No settings configured</h3>
                                <p class="mt-1 text-sm" style="color: var(--color-text-secondary);">Get started by creating your first payroll setting.</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        @if($activeTab === 'templates')
            <!-- Templates Tab - Placeholder -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Payroll Templates
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        Template management coming soon...
                    </p>
                </div>
            </div>
        @endif

        @if($activeTab === 'tax')
            <!-- Tax Brackets Tab - Placeholder -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Tax Brackets
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        Tax bracket management coming soon...
                    </p>
                </div>
            </div>
        @endif

        @if($activeTab === 'deductions')
            <!-- Deduction Types Tab - Placeholder -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        Deduction Types
                    </h2>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 dark:text-gray-400">
                        Deduction type management coming soon...
                    </p>
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="z-index: var(--z-modal);" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Backdrop -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true" style="background-color: var(--modal-overlay); backdrop-filter: blur(4px);">
                <div class="absolute inset-0 opacity-75"></div>
            </div>
            
            <!-- Modal positioning -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Modal content -->
            <div class="inline-block align-bottom rounded-xl px-6 pt-6 pb-6 text-left overflow-hidden transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 style="background-color: var(--color-surface-card); border: 1px solid var(--color-border-base); box-shadow: var(--color-surface-card-shadow);">
                
                <!-- Icon and content -->
                <div>
                    <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full mb-4"
                         style="background-color: var(--color-error-bg);">
                        <svg class="h-7 w-7" style="color: var(--color-error);" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-xl font-semibold mb-3" style="color: var(--color-text-primary);">
                            Delete Payroll Setting
                        </h3>
                        <div class="mb-6">
                            <p class="text-sm leading-relaxed" style="color: var(--color-text-secondary);">
                                Are you sure you want to delete <span class="font-medium" style="color: var(--color-text-primary);">"{{ $settingToDelete?->name }}"</span>? 
                                <br><span class="text-xs" style="color: var(--color-text-muted);">This action cannot be undone.</span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Action buttons -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-3 space-y-3 space-y-reverse sm:space-y-0">
                    <!-- Cancel button -->
                    <button wire:click="cancelDelete"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium rounded-lg border transition-all duration-200"
                            style="border-color: var(--color-border-base); color: var(--color-text-secondary); background-color: transparent;"
                            onmouseover="this.style.backgroundColor='var(--color-surface-card-hover)'; this.style.borderColor='var(--color-border-strong)';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='var(--color-border-base)';">
                        Cancel
                    </button>
                    
                    <!-- Delete button -->
                    <button wire:click="deleteSetting"
                            wire:loading.attr="disabled"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background-color: var(--color-error);"
                            onmouseover="this.style.backgroundColor='var(--color-error)'; this.style.opacity='0.9';"
                            onmouseout="this.style.backgroundColor='var(--color-error)'; this.style.opacity='1';">
                        <span wire:loading.remove wire:target="deleteSetting">Delete Setting</span>
                        <span wire:loading wire:target="deleteSetting" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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
    @endif
</div>
