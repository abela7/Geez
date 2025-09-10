<div class="admin-nav-root" 
     x-data="{ 
        sidebarOpen: false,
        sidebarCollapsed: (localStorage.getItem('sidebarCollapsed') === 'true' || localStorage.getItem('sidebarCollapsed') === null),
        activeSubmenu: '{{ request()->is('admin/staff*') ? 'staff' : (request()->is('admin/inventory*') ? 'inventory' : (request()->is('admin/sales*') || request()->is('admin/finance*') ? 'finance' : (request()->is('admin/menu*') ? 'menu' : (request()->is('admin/customers*') ? 'customers' : '')))) }}',
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
        },
        toggleSubmenu(menu) {
            this.activeSubmenu = this.activeSubmenu === menu ? '' : menu;
        }
     }"
     @toggle-sidebar.window="sidebarOpen = !sidebarOpen"
     @keydown.escape.window="sidebarOpen = false"
     x-effect="document.body.style.overflow = sidebarOpen ? 'hidden' : ''"
>
    <!-- Mobile Menu Backdrop -->
    <div class="sidebar-backdrop" 
         x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter="transition-opacity ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    <!-- Sidebar -->
    <aside class="admin-sidebar" 
           :class="{ 'mobile-open': sidebarOpen, 'collapsed': sidebarCollapsed }"
        role="navigation"
           aria-label="{{ __('dashboard.nav_main') }}">
        
    <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="sidebar-brand-wrap">
                <a href="/admin/dashboard" class="sidebar-brand">
                    <div class="sidebar-brand-icon">
                        <span>G</span>
                    </div>
                    <span class="sidebar-brand-text">{{ __('dashboard.app_name') }}</span>
                </a>
                
                <!-- Desktop Toggle -->
                <button @click="toggleSidebar()" 
                        class="sidebar-toggle desktop-only"
                        aria-label="{{ __('dashboard.toggle_sidebar') }}">
                    <svg class="icon-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile Close -->
            <button @click="sidebarOpen = false" 
                    class="sidebar-close mobile-only"
                    aria-label="{{ __('dashboard.close_menu') }}">
                <svg class="icon-close" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        </div>
        
        <!-- Navigation Links -->
        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <a href="/admin/dashboard" 
               class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="nav-text">{{ __('dashboard.nav_dashboard') }}</span>
            </a>

            <!-- Inventory (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'inventory' }">
                <button @click="toggleSubmenu('inventory')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/inventory*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="nav-text">{{ __('dashboard.nav_inventory') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'inventory'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="/admin/inventory" class="submenu-link {{ request()->is('admin/inventory') && !request()->is('admin/inventory/*') ? 'active' : '' }}">
                        {{ __('inventory.nav_title') }} {{ __('common.overview') }}
                    </a>
                    <a href="/admin/inventory/stock-levels" class="submenu-link {{ request()->is('admin/inventory/stock-levels*') ? 'active' : '' }}">
                        {{ __('inventory.stock_levels.title') }}
                    </a>
                    <a href="/admin/inventory/ingredients" class="submenu-link {{ request()->is('admin/inventory/ingredients*') ? 'active' : '' }}">
                        {{ __('inventory.ingredients.title') }}
                    </a>
                    <a href="/admin/inventory/settings" class="submenu-link {{ request()->is('admin/inventory/settings*') ? 'active' : '' }}">
                        {{ __('inventory.settings.nav_title') }}
                    </a>
                    <a href="/admin/inventory/recipes" class="submenu-link {{ request()->is('admin/inventory/recipes*') ? 'active' : '' }}">
                        {{ __('inventory.recipes.title') }}
                    </a>
                    <a href="/admin/inventory/movements" class="submenu-link {{ request()->is('admin/inventory/movements*') ? 'active' : '' }}">
                        {{ __('inventory.movements.nav_title') }}
                    </a>
                    <a href="/admin/inventory/locations" class="submenu-link {{ request()->is('admin/inventory/locations*') ? 'active' : '' }}">
                        {{ __('inventory.locations.nav_title') }}
                    </a>
                    <a href="/admin/inventory/purchasing" class="submenu-link {{ request()->is('admin/inventory/purchasing*') ? 'active' : '' }}">
                        {{ __('inventory.purchasing.nav_title') }}
                    </a>
                    <a href="/admin/inventory/suppliers" class="submenu-link {{ request()->is('admin/inventory/suppliers*') ? 'active' : '' }}">
                        {{ __('inventory.suppliers.nav_title') }}
                    </a>
                    <a href="/admin/inventory/alerts" class="submenu-link {{ request()->is('admin/inventory/alerts*') ? 'active' : '' }}">
                        {{ __('inventory.alerts.nav_title') }}
                    </a>
                    <a href="/admin/inventory/analytics" class="submenu-link {{ request()->is('admin/inventory/analytics*') ? 'active' : '' }}">
                        {{ __('inventory.analytics.nav_title') }}
                    </a>
                    <a href="/admin/inventory/stocktakes" class="submenu-link {{ request()->is('admin/inventory/stocktakes*') ? 'active' : '' }}">
                        {{ __('inventory.stocktakes.nav_title') }}
                    </a>
                </div>
            </div>

            <!-- Sales & Finance (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'finance' }">
                <button @click="toggleSubmenu('finance')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/sales*') || request()->is('admin/finance*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="nav-text">{{ __('finance.nav_title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'finance'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="/admin/sales" class="submenu-link {{ request()->is('admin/sales') && !request()->is('admin/sales/*') ? 'active' : '' }}">
                        {{ __('finance.sales_reports.title') }}
                    </a>
                    <a href="/admin/finance/expenses" class="submenu-link {{ request()->is('admin/finance/expenses*') ? 'active' : '' }}">
                        {{ __('finance.expenses') }}
                    </a>
                    <a href="/admin/finance/budgeting" class="submenu-link {{ request()->is('admin/finance/budgeting*') ? 'active' : '' }}">
                        {{ __('finance.budgeting.title') }}
                    </a>
                    <a href="/admin/finance/reports" class="submenu-link {{ request()->is('admin/finance/reports*') ? 'active' : '' }}">
                        {{ __('finance.financial_reports.title') }}
                    </a>
                    <a href="/admin/finance/settings" class="submenu-link {{ request()->is('admin/finance/settings*') ? 'active' : '' }}">
                        {{ __('finance.settings.nav_title') }}
                    </a>
                </div>
            </div>

            <!-- Staff (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'staff' }">
                <button @click="toggleSubmenu('staff')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/staff*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="nav-text">{{ __('dashboard.nav_staff') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'staff'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="/admin/staff" class="submenu-link {{ request()->is('admin/staff') && !request()->is('admin/staff/*') ? 'active' : '' }}">
                        {{ __('staff.nav_overview') }}
                    </a>
                    <a href="/admin/staff/directory" class="submenu-link {{ request()->is('admin/staff/directory*') ? 'active' : '' }}">
                        {{ __('staff.nav_directory') }}
                    </a>
                    <a href="/admin/staff/performance" class="submenu-link {{ request()->is('admin/staff/performance*') ? 'active' : '' }}">
                        {{ __('staff.nav_performance') }}
                    </a>
                    <a href="/admin/staff/attendance" class="submenu-link {{ request()->is('admin/staff/attendance*') ? 'active' : '' }}">
                        {{ __('staff.nav_attendance') }}
                    </a>
                    <a href="/admin/staff/tasks" class="submenu-link {{ request()->is('admin/staff/tasks*') ? 'active' : '' }}">
                        {{ __('staff.nav_tasks') }}
                    </a>
                    <a href="/admin/staff/payroll" class="submenu-link {{ request()->is('admin/staff/payroll*') ? 'active' : '' }}">
                        {{ __('staff.nav_payroll') }}
                    </a>
                </div>
            </div>

            <!-- Menu Management (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'menu' }">
                <button @click="toggleSubmenu('menu')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/menu*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="nav-text">{{ __('menu.nav_title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'menu'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="/admin/menu/food-items" class="submenu-link {{ request()->is('admin/menu/food-items*') ? 'active' : '' }}">
                        {{ __('menu.food_items.title') }}
                    </a>
                    <a href="/admin/menu/categories" class="submenu-link {{ request()->is('admin/menu/categories*') ? 'active' : '' }}">
                        {{ __('menu.categories.title') }}
                    </a>
                    <a href="/admin/menu/modifiers" class="submenu-link {{ request()->is('admin/menu/modifiers*') ? 'active' : '' }}">
                        {{ __('menu.modifiers.title') }}
                    </a>
                    <a href="/admin/menu/dish-cost" class="submenu-link {{ request()->is('admin/menu/dish-cost*') ? 'active' : '' }}">
                        {{ __('menu.dish_cost.title') }}
                    </a>
                    <a href="/admin/menu/pricing" class="submenu-link {{ request()->is('admin/menu/pricing*') ? 'active' : '' }}">
                        {{ __('menu.pricing.title') }}
                    </a>
                    <a href="/admin/menu/design" class="submenu-link {{ request()->is('admin/menu/design*') ? 'active' : '' }}">
                        {{ __('menu.design.title') }}
                    </a>
                </div>
            </div>

            <!-- Customer Management (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'customers' }">
                <button @click="toggleSubmenu('customers')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/customers*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="nav-text">{{ __('customers.nav_title') }}</span>
                    <svg class="nav-chevron" :class="{ 'rotated': activeSubmenu === 'customers' }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
                <div class="submenu" x-show="activeSubmenu === 'customers'" x-transition>
                    <a href="/admin/customers/directory" class="submenu-link {{ request()->is('admin/customers/directory*') ? 'active' : '' }}">
                        {{ __('customers.directory.title') }}
                    </a>
                    <a href="/admin/customers/loyalty" class="submenu-link {{ request()->is('admin/customers/loyalty*') ? 'active' : '' }}">
                        {{ __('customers.loyalty.title') }}
                    </a>
                    <a href="/admin/customers/reservations" class="submenu-link {{ request()->is('admin/customers/reservations*') ? 'active' : '' }}">
                        {{ __('customers.reservations.title') }}
                    </a>
                    <a href="/admin/customers/analytics" class="submenu-link {{ request()->is('admin/customers/analytics*') ? 'active' : '' }}">
                        {{ __('customers.analytics.title') }}
                    </a>
                    <a href="/admin/customers/feedback" class="submenu-link {{ request()->is('admin/customers/feedback*') ? 'active' : '' }}">
                        {{ __('customers.feedback.title') }}
                    </a>
                </div>
            </div>

            <!-- Reports -->
            <a href="/admin/reports" 
               class="nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="nav-text">{{ __('dashboard.nav_reports') }}</span>
            </a>

            <!-- Settings -->
            <a href="/admin/settings" 
               class="nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
                <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="nav-text">{{ __('dashboard.nav_settings') }}</span>
            </a>
    </nav>

    <!-- Sidebar Footer -->
        <div class="sidebar-footer">
                <!-- Theme Toggle -->
            <button @click="toggleTheme()" 
                    class="footer-btn"
                    aria-label="{{ __('dashboard.theme_toggle') }}">
                <svg class="footer-icon dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                <svg class="footer-icon hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                <span class="footer-text">
                    <span class="dark:hidden">{{ __('dashboard.dark_mode') }}</span>
                        <span class="hidden dark:block">{{ __('dashboard.light_mode') }}</span>
                    </span>
                </button>

            <!-- Language Selector -->
            <div class="relative" x-data="{ langOpen: false }">
                <button @click="langOpen = !langOpen" 
                        class="footer-btn"
                        aria-label="{{ __('dashboard.language_switch') }}">
                    <svg class="footer-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                        </svg>
                    <span class="footer-text">{{ strtoupper(app()->getLocale()) }}</span>
                    <svg class="footer-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <!-- Language Dropdown -->
                <div x-show="langOpen" 
                     @click.away="langOpen = false"
                     x-transition
                     class="language-dropdown">
                        <a href="{{ route('language.switch', 'en') }}" 
                       class="language-option {{ app()->getLocale() == 'en' ? 'active' : '' }}">
                            {{ __('dashboard.language_english') }}
                        </a>
                        <a href="{{ route('language.switch', 'am') }}" 
                       class="language-option {{ app()->getLocale() == 'am' ? 'active' : '' }}">
                            {{ __('dashboard.language_amharic') }}
                        </a>
                        <a href="{{ route('language.switch', 'ti') }}" 
                       class="language-option {{ app()->getLocale() == 'ti' ? 'active' : '' }}">
                            {{ __('dashboard.language_tigrinya') }}
                        </a>
                </div>
            </div>

            

            <!-- Version -->
            <div class="sidebar-version">
                <span>v2025.1</span>
            </div>
    </div>
</aside>
</div>