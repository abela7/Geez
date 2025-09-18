<!-- Admin Header -->
<div class="header-content">
    <!-- Left Section -->
    <div class="header-left">
        <!-- Mobile Sidebar Toggle -->
        <button 
            @click="$dispatch('toggle-sidebar')"
            class="mobile-menu-toggle header-control header-control--icon"
            aria-label="{{ __('dashboard.open_sidebar') }}"
        >
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Page Title -->
        <h1 class="page-title-header">
            {{ $pageTitle }}
        </h1>
        
        <!-- Search Bar (Desktop) -->
        <div class="header-search">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                type="text"
                class="search-input"
                placeholder="{{ __('dashboard.search_placeholder') }}"
            >
        </div>
    </div>

    <!-- Right Section -->
    <div class="header-right">
        <!-- Notifications -->
        <div class="relative">
            <button 
                wire:click="toggleNotifications"
                class="notification-btn header-control header-control--icon"
                aria-label="{{ __('dashboard.notifications') }}"
            >
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if($notificationCount > 0)
                <span class="notification-badge">
                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                </span>
                @endif
            </button>
        </div>
        
        <!-- User Menu -->
        <div class="user-menu" x-data="{ open: false }">
            <button 
                @click="open = !open"
                class="user-menu-trigger header-control"
                :aria-expanded="open"
                aria-label="{{ __('dashboard.user_menu') }}"
            >
                <div class="user-avatar">
                    {{ substr(auth()->user()->first_name ?? 'A', 0, 1) }}
                </div>
                <div class="user-info">
                    <span class="user-name">
                        {{ auth()->user()->full_name ?? 'Admin' }}
                    </span>
                    <span class="user-role">
                        {{ auth()->user()->staffType->display_name ?? __('dashboard.admin_role') }}
                    </span>
                </div>
                <svg class="user-menu-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div 
                x-show="open" 
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="user-dropdown"
                style="display: none;"
            >
                <a href="#" class="dropdown-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>{{ __('dashboard.user_profile') }}</span>
                </a>
                
                <a href="#" class="dropdown-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ __('dashboard.user_settings') }}</span>
                </a>
                
                <div class="dropdown-divider"></div>
                
                <form method="POST" action="{{ route('admin.logout') }}" class="dropdown-item-form">
                    @csrf
                    <button type="submit" class="dropdown-item w-full text-left">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>{{ __('dashboard.user_logout') }}</span>
                    </button>
                </form>
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

