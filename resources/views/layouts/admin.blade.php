<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      dir="{{ in_array(app()->getLocale(), ['ar', 'he']) ? 'rtl' : 'ltr' }}"
      class="{{ Cookie::get('theme', 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Geez Restaurant'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+Ethiopic:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Dark Mode Script - Must run before page renders to prevent flash -->
    <script>
        // Immediately check and apply theme before page renders
        (function() {
            const theme = localStorage.theme;
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (!theme && prefersDark);
            
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Core Styles -->
    @vite(['resources/css/app.css', 'resources/css/admin/layout.css', 'resources/css/admin/modal-fix.css', 'resources/js/admin/modal-system.js'])
    
    <!-- Page Specific Styles -->
    @stack('styles')

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <!-- Admin Layout Container -->
    <div class="admin-layout">
        <!-- Sidebar Component -->
        @livewire('admin.navigation')
        
        <!-- Main Content Area -->
        <div class="admin-main pt-16">  <!-- Add pt-16 for fixed header offset -->
            <!-- Header Component -->
            <header class="admin-header fixed top-0 left-0 right-0 z-50 bg-primary shadow-lg">  <!-- Make fixed with z-index -->
                @livewire('admin.header', ['pageTitle' => $pageTitle ?? __('dashboard.title')])
            </header>
            
            <!-- Page Content -->
            <main class="admin-content relative z-10" role="main" aria-label="{{ __('Main content area') }}">  <!-- z-10 below nav; relative for stacking -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                {{ $errors->first() }}
            </div>
        @endif
                @yield('content')
            </main>
            
            <!-- Footer Component -->
            @include('components.admin.footer')
        </div>
    </div>

    <!-- Core Scripts (Vite - includes Alpine) -->
    @vite(['resources/js/app.js', 'resources/js/admin/layout.js'])

    <!-- Livewire Scripts (load AFTER Alpine) -->
    @livewireScripts

    <!-- Page Specific Scripts -->
    @stack('scripts')
</body>
</html>
