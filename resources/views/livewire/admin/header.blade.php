<!-- Admin Header -->
<div class="flex items-center justify-between px-4 lg:px-6 py-4">
    <!-- Left: Mobile Toggle + Page Title -->
    <div class="flex items-center gap-3 flex-1">
        <!-- Mobile Sidebar Toggle -->
        <button 
            @click="$dispatch('toggle-sidebar')"
            class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            aria-label="{{ __('dashboard.open_sidebar') }}"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Page Title -->
        <h1 class="text-xl md:text-2xl font-semibold text-primary">
            {{ $pageTitle }}
        </h1>
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-3">
        <!-- Notifications -->
        <div class="relative">
            <button 
                wire:click="toggleNotifications"
                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative"
                aria-label="{{ __('dashboard.notifications') }}"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if($notificationCount > 0)
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                    {{ $notificationCount }}
                </span>
                @endif
            </button>
        </div>
        
        <!-- User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button 
                @click="open = !open"
                class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-[var(--color-bg-tertiary)] transition-colors"
                :aria-expanded="open"
                aria-label="{{ __('dashboard.user_menu') }}"
            >
                <div class="w-8 h-8 rounded-full bg-primary text-on-primary flex items-center justify-center font-semibold">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                </div>
                <span class="hidden md:block text-sm font-medium text-primary">
                    {{ auth()->user()->name ?? 'Admin' }}
                </span>
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 max-w-[calc(100vw_-_2rem)] w-56 rounded-lg bg-[var(--color-bg-secondary)] shadow-lg border border-[var(--color-border)] overflow-hidden z-50"
                style="display: none;"
            >
                <div class="py-1">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-[var(--color-bg-tertiary)] transition-colors">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ __('dashboard.user_profile') }}</span>
                    </a>
                    <div class="h-px bg-[var(--color-border)] my-1"></div>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-[var(--color-bg-tertiary)] transition-colors">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-sm font-medium">{{ __('dashboard.user_logout') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    html.classList.toggle('dark');
    localStorage.setItem('theme', newTheme);
    
    // Update cookie for server-side
    document.cookie = `theme=${newTheme};path=/;max-age=${60*60*24*365}`;
}
</script>