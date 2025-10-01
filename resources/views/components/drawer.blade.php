@props([
    'show' => false,
    'title' => '',
    'withCloseButton' => true,
    'width' => 'w-11/12 lg:w-1/3',
    'position' => 'left' // left, right, top, bottom
])

@php
    $positionClasses = [
        'left' => 'left-0 top-0 h-full',
        'right' => 'right-0 top-0 h-full',
        'top' => 'top-0 left-0 w-full',
        'bottom' => 'bottom-0 left-0 w-full'
    ];
    
    $slideClasses = [
        'left' => 'transform -translate-x-full',
        'right' => 'transform translate-x-full',
        'top' => 'transform -translate-y-full',
        'bottom' => 'transform translate-y-full'
    ];
@endphp

<div 
    x-show="showAttendanceDrawer"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 drawer-overlay overflow-hidden"
    style="display: none;"
    x-cloak
>
    <!-- Backdrop -->
    <div 
        class="absolute inset-0 bg-black bg-opacity-50"
        @click="showAttendanceDrawer = false"
    ></div>

    <!-- Drawer Panel -->
    <div 
        class="absolute {{ $positionClasses[$position] }} {{ $width }} bg-white dark:bg-gray-800 shadow-xl h-full flex flex-col"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="{{ $slideClasses[$position] }}"
        x-transition:enter-end="transform translate-x-0 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="transform translate-x-0 translate-y-0"
        x-transition:leave-end="{{ $slideClasses[$position] }}"
    >
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ $title }}
            </h3>
            
            @if($withCloseButton)
                <button 
                    @click="showAttendanceDrawer = false"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>

        <!-- Content -->
        <div class="flex-1 p-6 overflow-hidden">
            <div class="drawer-content">
                {{ $slot }}
            </div>
        </div>

        <!-- Footer/Actions -->
        @if(isset($actions))
            <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
