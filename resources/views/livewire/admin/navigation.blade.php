<div class="admin-nav-root" 
     x-data="{ 
        sidebarOpen: false,
        sidebarCollapsed: (localStorage.getItem('sidebarCollapsed') === 'true' || localStorage.getItem('sidebarCollapsed') === null),
        activeSubmenu: '{{ request()->is('admin/staff*') || request()->is('admin/shifts*') ? 'staff' : (request()->is('admin/inventory*') ? 'inventory' : (request()->is('admin/sales*') || request()->is('admin/finance*') ? 'finance' : (request()->is('admin/menu*') ? 'menu' : (request()->is('admin/customers*') ? 'customers' : (request()->is('admin/reports*') ? 'reports' : (request()->is('admin/tables*') ? 'tables' : (request()->is('admin/bar*') ? 'bar' : (request()->is('admin/injera*') ? 'injera' : (request()->is('admin/todos*') ? 'todos' : (request()->is('admin/activities*') ? 'activities' : '')))))))))) }}',
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
     @keydown.window="if (($event.ctrlKey || $event.metaKey) && $event.key === 'o') { $event.preventDefault(); if (window.innerWidth < 1024) { sidebarOpen = !sidebarOpen; } else { toggleSidebar(); } }"
     x-effect="document.body.style.overflow = sidebarOpen ? 'hidden' : ''"
>
    <!-- Mobile Menu Backdrop -->
    <div class="sidebar-backdrop" 
         x-show="sidebarOpen"
         x-cloak
         @click.self="sidebarOpen = false"
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
           x-cloak
        role="navigation"
           aria-label="{{ __('dashboard.nav_main') }}">
        
    <!-- Sidebar Header -->
        <div class="sidebar-header">
            <div class="sidebar-brand-wrap">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <div class="sidebar-brand-logo">
                        @if(config('app.logo_url'))
                            <img src="{{ config('app.logo_url') }}" 
                                 alt="{{ __('dashboard.app_name') }}" 
                                 class="brand-logo-image">
                        @else
                            <div class="brand-logo-placeholder">
                                <svg class="logo-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
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
        <nav class="sidebar-nav" @click="if($event.target.closest('a') && window.innerWidth < 1024) { sidebarOpen = false }">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
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
                    <a href="{{ route('admin.inventory.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.index') ? 'active' : '' }}">
                        {{ __('inventory.nav_title') }} {{ __('common.overview') }}
                    </a>
                    <a href="{{ route('admin.inventory.stock-levels.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.stock-levels.*') ? 'active' : '' }}">
                        {{ __('inventory.stock_levels.title') }}
                    </a>
                    <a href="{{ route('admin.inventory.ingredients.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.ingredients.*') ? 'active' : '' }}">
                        {{ __('inventory.ingredients.title') }}
                    </a>
                    <a href="{{ route('admin.inventory.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.settings.*') ? 'active' : '' }}">
                        {{ __('inventory.settings.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.recipes.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.recipes.*') ? 'active' : '' }}">
                        {{ __('inventory.recipes.title') }}
                    </a>
                    <a href="{{ route('admin.inventory.movements.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.movements.*') ? 'active' : '' }}">
                        {{ __('inventory.movements.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.locations.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.locations.*') ? 'active' : '' }}">
                        {{ __('inventory.locations.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.purchasing.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.purchasing.*') ? 'active' : '' }}">
                        {{ __('inventory.purchasing.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.suppliers.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.suppliers.*') ? 'active' : '' }}">
                        {{ __('inventory.suppliers.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.alerts.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.alerts.*') ? 'active' : '' }}">
                        {{ __('inventory.alerts.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.analytics.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.analytics.*') ? 'active' : '' }}">
                        {{ __('inventory.analytics.nav_title') }}
                    </a>
                    <a href="{{ route('admin.inventory.stocktakes.index') }}" class="submenu-link {{ request()->routeIs('admin.inventory.stocktakes.*') ? 'active' : '' }}">
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
                    <a href="{{ route('admin.sales.index') }}" class="submenu-link {{ request()->routeIs('admin.sales.index') ? 'active' : '' }}">
                        {{ __('finance.sales_reports.title') }}
                    </a>
                    <a href="{{ route('admin.finance.tips.index') }}" class="submenu-link {{ request()->routeIs('admin.finance.tips.*') ? 'active' : '' }}">
                        {{ __('finance.tips.title') }}
                    </a>
                    <a href="{{ route('admin.finance.expenses.index') }}" class="submenu-link {{ request()->routeIs('admin.finance.expenses.*') ? 'active' : '' }}">
                        {{ __('finance.expenses.title') }}
                    </a>
                    <a href="{{ route('admin.finance.budgeting.index') }}" class="submenu-link {{ request()->routeIs('admin.finance.budgeting.*') ? 'active' : '' }}">
                        {{ __('finance.budgeting.title') }}
                    </a>
                    <a href="{{ route('admin.finance.reports.index') }}" class="submenu-link {{ request()->routeIs('admin.finance.reports.*') ? 'active' : '' }}">
                        {{ __('finance.financial_reports.title') }}
                    </a>
                    <a href="{{ route('admin.finance.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.finance.settings.*') ? 'active' : '' }}">
                        {{ __('finance.settings.nav_title') }}
                    </a>
                </div>
            </div>

            <!-- Staff (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'staff' }">
                <button @click="toggleSubmenu('staff')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/staff*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M17 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2"></path>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
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
                    <a href="{{ route('admin.staff.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.index') ? 'active' : '' }}">
                        {{ __('staff.nav_overview') }}
                    </a>
                    <a href="{{ route('admin.staff.directory.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.directory.*') ? 'active' : '' }}">
                        {{ __('staff.nav_directory') }}
                    </a>
                    <a href="{{ route('admin.staff.performance.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.performance.*') ? 'active' : '' }}">
                        {{ __('staff.nav_performance') }}
                    </a>
                    <a href="{{ route('admin.staff.attendance.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.attendance.*') ? 'active' : '' }}">
                        {{ __('staff.nav_attendance') }}
                    </a>
                    <a href="{{ route('admin.staff.tasks.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.tasks.index') || request()->routeIs('admin.staff.tasks.show') || request()->routeIs('admin.staff.tasks.create') || request()->routeIs('admin.staff.tasks.edit') ? 'active' : '' }}">
                        {{ __('staff.nav_tasks') }}
                    </a>
                    <a href="{{ route('admin.staff.tasks.today') }}" class="submenu-link {{ request()->routeIs('admin.staff.tasks.today') ? 'active' : '' }}">
                        {{ __("Today's Tasks") }}
                    </a>
                    <a href="{{ route('admin.staff.tasks.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.tasks.settings.*') ? 'active' : '' }}">
                        {{ __('staff.nav_task_settings') }}
                    </a>
                    <a href="{{ route('admin.staff.payroll.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.payroll.*') ? 'active' : '' }}">
                        {{ __('staff.nav_payroll') }}
                    </a>
                    <div class="submenu-separator"></div>
                    <div class="submenu-section-title">{{ __('shifts.management.section_title') }}</div>
                    <a href="{{ route('admin.shifts.overview.index') }}" class="submenu-link {{ request()->routeIs('admin.shifts.overview.*') ? 'active' : '' }}">
                        {{ __('shifts.overview.title') }}
                    </a>
                    <a href="{{ route('admin.shifts.manage.index') }}" class="submenu-link {{ request()->routeIs('admin.shifts.manage.*') ? 'active' : '' }}">
                        {{ __('shifts.manage.title') }}
                    </a>
                    <a href="{{ route('admin.shifts.assignments.index') }}" class="submenu-link {{ request()->routeIs('admin.shifts.assignments.*') ? 'active' : '' }}">
                        {{ __('shifts.assignments.title') }}
                    </a>
                    <a href="{{ route('admin.shifts.shifts.templates.index') }}" class="submenu-link {{ request()->routeIs('admin.shifts.shifts.templates.*') ? 'active' : '' }}">
                        {{ __('shifts.templates.title') }}
                    </a>
                    <div class="submenu-separator"></div>
                    <div class="submenu-section-title">{{ __('staff.settings.section_title') }}</div>
                    <a href="{{ route('admin.staff.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.staff.settings.*') ? 'active' : '' }}">
                        {{ __('staff.settings.nav_title') }}
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
                    <a href="{{ route('admin.menu.food-items.index') }}" class="submenu-link {{ request()->routeIs('admin.menu.food-items.*') ? 'active' : '' }}">
                        {{ __('menu.food_items.title') }}
                    </a>
                    <a href="{{ route('admin.menu.categories.index') }}" class="submenu-link {{ request()->routeIs('admin.menu.categories.*') ? 'active' : '' }}">
                        {{ __('menu.categories.title') }}
                    </a>
                    <a href="{{ route('admin.menu.modifiers.index') }}" class="submenu-link {{ request()->routeIs('admin.menu.modifiers.*') ? 'active' : '' }}">
                        {{ __('menu.modifiers.title') }}
                    </a>
                    <a href="{{ route('admin.menu.dish-cost.index') }}" class="submenu-link {{ request()->routeIs('admin.menu.dish-cost.*') ? 'active' : '' }}">
                        {{ __('menu.dish_cost.title') }}
                    </a>
                    <a href="{{ route('admin.menu.pricing.index') }}" class="submenu-link {{ request()->routeIs('admin.menu.pricing.*') ? 'active' : '' }}">
                        {{ __('menu.pricing.title') }}
                    </a>
                    <a href="{{ route('admin.menu.design.index') }}" class="submenu-link {{ request()->routeIs('admin.menu.design.*') ? 'active' : '' }}">
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
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'customers'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.customers.directory.index') }}" class="submenu-link {{ request()->routeIs('admin.customers.directory.*') ? 'active' : '' }}">
                        {{ __('customers.directory.title') }}
                    </a>
                    <a href="{{ route('admin.customers.loyalty.index') }}" class="submenu-link {{ request()->routeIs('admin.customers.loyalty.*') ? 'active' : '' }}">
                        {{ __('customers.loyalty.title') }}
                    </a>
                    <a href="{{ route('admin.customers.reservations.index') }}" class="submenu-link {{ request()->routeIs('admin.customers.reservations.*') ? 'active' : '' }}">
                        {{ __('customers.reservations.title') }}
                    </a>
                    <a href="{{ route('admin.customers.analytics.index') }}" class="submenu-link {{ request()->routeIs('admin.customers.analytics.*') ? 'active' : '' }}">
                        {{ __('customers.analytics.title') }}
                    </a>
                    <a href="{{ route('admin.customers.feedback.index') }}" class="submenu-link {{ request()->routeIs('admin.customers.feedback.*') ? 'active' : '' }}">
                        {{ __('customers.feedback.title') }}
                    </a>
                </div>
            </div>

            <!-- Table & Room Management (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'tables' }">
                <button @click="toggleSubmenu('tables')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/tables*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="nav-text">{{ __('tables.nav_title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'tables'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.tables.rooms.index') }}" class="submenu-link {{ request()->routeIs('admin.tables.rooms.*') ? 'active' : '' }}">
                        {{ __('tables.rooms.title') }}
                    </a>
                    <a href="{{ route('admin.tables.categories.index') }}" class="submenu-link {{ request()->routeIs('admin.tables.categories.*') ? 'active' : '' }}">
                        {{ __('tables.categories.title') }}
                    </a>
                    <a href="{{ route('admin.tables.types.index') }}" class="submenu-link {{ request()->routeIs('admin.tables.types.*') ? 'active' : '' }}">
                        {{ __('tables.types.title') }}
                    </a>
                    <a href="{{ route('admin.tables.layout.index') }}" class="submenu-link {{ request()->routeIs('admin.tables.layout.*') ? 'active' : '' }}">
                        {{ __('tables.layout.title') }}
                    </a>
                </div>
            </div>

            <!-- Bar Management (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'bar' }">
                <button @click="toggleSubmenu('bar')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/bar*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M19 3H5l7 8 7-8Z"></path>
                        <path d="M12 11v8"></path>
                        <path d="M8 22h8"></path>
                    </svg>
                    <span class="nav-text">{{ __('bar.nav_title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'bar'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.bar.inventory.index') }}" class="submenu-link {{ request()->routeIs('admin.bar.inventory.*') ? 'active' : '' }}">
                        {{ __('bar.inventory.title') }}
                    </a>
                    <a href="{{ route('admin.bar.recipes.index') }}" class="submenu-link {{ request()->routeIs('admin.bar.recipes.*') ? 'active' : '' }}">
                        {{ __('bar.recipes.title') }}
                    </a>
                    <a href="{{ route('admin.bar.pricing.index') }}" class="submenu-link {{ request()->routeIs('admin.bar.pricing.*') ? 'active' : '' }}">
                        {{ __('bar.pricing.title') }}
                    </a>
                    <a href="{{ route('admin.bar.analytics.index') }}" class="submenu-link {{ request()->routeIs('admin.bar.analytics.*') ? 'active' : '' }}">
                        {{ __('bar.analytics.title') }}
                    </a>
                    <a href="{{ route('admin.bar.suppliers.index') }}" class="submenu-link {{ request()->routeIs('admin.bar.suppliers.*') ? 'active' : '' }}">
                        {{ __('bar.suppliers.title') }}
                    </a>
                    <a href="{{ route('admin.bar.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.bar.settings.*') ? 'active' : '' }}">
                        {{ __('bar.settings.title') }}
                    </a>
                </div>
            </div>

            <!-- Injera Management (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'injera' }">
                <button @click="toggleSubmenu('injera')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/injera*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="nav-text">{{ __('injera.nav_title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'injera'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.injera.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.index') ? 'active' : '' }}">
                        {{ __('injera.overview.title') }}
                    </a>
                    <a href="{{ route('admin.injera.flour-management.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.flour-management.*') ? 'active' : '' }}">
                        {{ __('injera.flour_management.title') }}
                    </a>
                    <a href="{{ route('admin.injera.bucket-configurations.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.bucket-configurations.*') ? 'active' : '' }}">
                        {{ __('injera.bucket_configurations.title') }}
                    </a>
                    <a href="{{ route('admin.injera.production-batches.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.production-batches.*') ? 'active' : '' }}">
                        {{ __('injera.production_batches.title') }}
                    </a>
                    <a href="{{ route('admin.injera.injera-stock-levels.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.injera-stock-levels.*') ? 'active' : '' }}">
                        {{ __('injera.injera_stock_levels.title') }}
                    </a>
                    <a href="{{ route('admin.injera.cost-analysis.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.cost-analysis.*') ? 'active' : '' }}">
                        {{ __('injera.cost_analysis.title') }}
                    </a>
                    <a href="{{ route('admin.injera.orders.index') }}" class="submenu-link {{ request()->routeIs('admin.injera.orders.*') ? 'active' : '' }}">
                        {{ __('injera.orders.title') }}
                    </a>
                </div>
            </div>

            <!-- Reports (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'reports' }">
                <button @click="toggleSubmenu('reports')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/reports*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="nav-text">{{ __('reports.nav_title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'reports'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.reports.sales.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.sales.*') ? 'active' : '' }}">
                        {{ __('reports.sales.title') }}
                    </a>
                    <a href="{{ route('admin.reports.customers.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.customers.*') ? 'active' : '' }}">
                        {{ __('reports.customers.title') }}
                    </a>
                    <a href="{{ route('admin.reports.menu.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.menu.*') ? 'active' : '' }}">
                        {{ __('reports.menu.title') }}
                    </a>
                    <a href="{{ route('admin.reports.inventory.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.inventory.*') ? 'active' : '' }}">
                        {{ __('reports.inventory.title') }}
                    </a>
                    <a href="{{ route('admin.reports.staff.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.staff.*') ? 'active' : '' }}">
                        {{ __('reports.staff.title') }}
                    </a>
                    <a href="{{ route('admin.reports.financial.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.financial.*') ? 'active' : '' }}">
                        {{ __('reports.financial.title') }}
                    </a>
                    <a href="{{ route('admin.reports.operational.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.operational.*') ? 'active' : '' }}">
                        {{ __('reports.operational.title') }}
                    </a>
                    <a href="{{ route('admin.reports.executive.index') }}" class="submenu-link {{ request()->routeIs('admin.reports.executive.*') ? 'active' : '' }}">
                        {{ __('reports.executive.title') }}
                    </a>
                </div>
            </div>

            <!-- To-Do Management (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'todos' }">
                <button @click="toggleSubmenu('todos')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/todos*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="nav-text">{{ __('todos.management.title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'todos'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.todos.overview.index') }}" class="submenu-link {{ request()->routeIs('admin.todos.overview.*') ? 'active' : '' }}">
                        {{ __('todos.overview.title') }}
                    </a>
                    <a href="{{ route('admin.todos.staff-lists.index') }}" class="submenu-link {{ request()->routeIs('admin.todos.staff-lists.*') ? 'active' : '' }}">
                        {{ __('todos.staff_lists.title') }}
                    </a>
                    <a href="{{ route('admin.todos.templates.index') }}" class="submenu-link {{ request()->routeIs('admin.todos.templates.*') ? 'active' : '' }}">
                        {{ __('todos.templates.title') }}
                    </a>
                    <a href="{{ route('admin.todos.schedules.index') }}" class="submenu-link {{ request()->routeIs('admin.todos.schedules.*') ? 'active' : '' }}">
                        {{ __('todos.schedules.title') }}
                    </a>
                    <a href="{{ route('admin.todos.progress.index') }}" class="submenu-link {{ request()->routeIs('admin.todos.progress.*') ? 'active' : '' }}">
                        {{ __('todos.progress.title') }}
                    </a>
                </div>
            </div>

            <!-- Activity Tracking (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'activities' }">
                <button @click="toggleSubmenu('activities')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/activities*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="nav-text">{{ __('activities.management.title') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'activities'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.activities.manage.index') }}" class="submenu-link {{ request()->routeIs('admin.activities.manage.*') ? 'active' : '' }}">
                        {{ __('activities.manage.title') }}
                    </a>
                    <a href="{{ route('admin.activities.logging.index') }}" class="submenu-link {{ request()->routeIs('admin.activities.logging.*') ? 'active' : '' }}">
                        {{ __('activities.logging.title') }}
                    </a>
                    <a href="{{ route('admin.activities.analytics.index') }}" class="submenu-link {{ request()->routeIs('admin.activities.analytics.*') ? 'active' : '' }}">
                        {{ __('activities.analytics.title') }}
                    </a>
                    <a href="{{ route('admin.activities.assignments.index') }}" class="submenu-link {{ request()->routeIs('admin.activities.assignments.*') ? 'active' : '' }}">
                        {{ __('activities.assignments.title') }}
                    </a>
                </div>
            </div>

            <!-- Settings (With Submenu) -->
            <div class="nav-group" :class="{ 'active': activeSubmenu === 'settings' }">
                <button @click="toggleSubmenu('settings')" 
                        class="nav-link nav-link--parent {{ request()->is('admin/settings*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="nav-text">{{ __('dashboard.nav_settings') }}</span>
                    <svg class="nav-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div class="nav-submenu" 
                     x-show="activeSubmenu === 'settings'" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 max-h-0"
                     x-transition:enter-end="opacity-100 max-h-96"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 max-h-96"
                     x-transition:leave-end="opacity-0 max-h-0">
                    <a href="{{ route('admin.settings.index') }}" class="submenu-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                        {{ __('settings.general.title') }}
                    </a>
                    <div class="submenu-separator"></div>
                    <div class="submenu-section-title">{{ __('settings.shift_management.section_title') }}</div>
                    <a href="{{ route('admin.settings.departments.index') }}" class="submenu-link {{ request()->routeIs('admin.settings.departments.*') ? 'active' : '' }}">
                        {{ __('admin.departments.title') }}
                    </a>
                    <a href="{{ route('admin.settings.shift-types.index') }}" class="submenu-link {{ request()->routeIs('admin.settings.shift-types.*') ? 'active' : '' }}">
                        {{ __('admin.shift_types.title') }}
                    </a>
                </div>
            </div>
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