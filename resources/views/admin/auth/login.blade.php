<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ __('admin.auth.login.title') }} - {{ config('app.name') }}</title>
    
    <!-- Using system fonts for offline compatibility -->
    
    <!-- Dark Mode Script -->
    <script>
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
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .geez-gradient {
            background: linear-gradient(135deg, #CDAF56 0%, #301934 100%);
        }
        
        .login-card-shadow {
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04),
                0 0 0 1px rgba(205, 175, 86, 0.05);
        }
        
        .dark .login-card-shadow {
            box-shadow: 
                0 20px 25px -5px rgba(0, 0, 0, 0.4),
                0 10px 10px -5px rgba(0, 0, 0, 0.2),
                0 0 0 1px rgba(205, 175, 86, 0.1);
        }
        
        .login-input {
            background: rgba(255, 255, 255, 0.95) !important;
            color: #000000 !important;
        }
        
        .dark .login-input {
            background: rgba(37, 35, 64, 0.95) !important;
            color: #FFFFFF !important;
        }
        
        .login-input::placeholder {
            color: rgba(107, 91, 115, 0.6) !important;
        }
        
        .dark .login-input::placeholder {
            color: rgba(155, 143, 163, 0.6) !important;
        }
        
        .login-input:focus {
            border-color: #CDAF56 !important;
            box-shadow: 0 0 0 3px rgba(205, 175, 86, 0.1) !important;
            transform: translateY(-2px);
        }
        
        .pulse-glow {
            animation: pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse-glow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.05); }
        }
    </style>
</head>
<body class="h-full font-sans antialiased bg-[#F8F6F1] dark:bg-[#1C1B2E] transition-colors duration-300">
    <div class="min-h-full relative">
        <!-- Theme Toggle -->
        <div class="absolute top-6 right-6 z-50">
            <button onclick="toggleTheme()" class="p-3 rounded-full bg-white/20 dark:bg-black/30 backdrop-blur-md border border-white/20 dark:border-white/10 hover:bg-white/30 dark:hover:bg-black/40 transition-colors duration-200" aria-label="Toggle theme">
                <svg class="w-5 h-5 text-[#301934] dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="5" stroke-width="2"/>
                    <path d="M12 1v2m0 18v2M4.22 4.22l1.42 1.42m12.72 12.72l1.42 1.42M1 12h2m18 0h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" stroke-width="2"/>
                </svg>
                <svg class="w-5 h-5 text-[#CDAF56] hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke-width="2"/>
                </svg>
            </button>
        </div>

        <div class="min-h-full flex flex-col justify-center py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="w-full max-w-md mx-auto">
                <!-- Logo with Glow -->
                <div class="flex justify-center mb-6">
                    <div class="relative">
                        <div class="absolute inset-0 geez-gradient rounded-full blur-3xl opacity-30 pulse-glow scale-125"></div>
                        <div class="absolute inset-0 geez-gradient rounded-full blur-xl opacity-20 pulse-glow scale-110"></div>
                        <div class="absolute inset-0 bg-[#CDAF56]/20 rounded-full blur-lg opacity-40 pulse-glow"></div>
                        <img class="relative h-16 md:h-20 w-16 md:w-20 z-10 drop-shadow-2xl" src="{{ asset('images/geez-logo-animated.svg') }}" alt="{{ config('app.name') }}">
                    </div>
                </div>
                
                <!-- Welcome Text -->
                <div class="text-center space-y-2 mb-8">
                    <h2 class="text-3xl md:text-4xl font-bold tracking-tight text-[#301934] dark:text-[#F8F6F1]">
                        {{ __('admin.auth.login.heading') }}
                    </h2>
                    <div class="w-24 h-1 geez-gradient rounded-full mx-auto mt-4"></div>
                </div>
            </div>

            <!-- Login Card -->
            <div class="w-full max-w-md mx-auto">
                <div class="bg-white/80 dark:bg-[#1C1B2E]/80 backdrop-blur-xl py-10 px-6 sm:px-10 login-card-shadow rounded-2xl border border-white/50 dark:border-[#3A3654]/50">
                    <form class="space-y-6" method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf
                        
                        <!-- Username Field -->
                        <div class="space-y-2">
                            <label for="username" class="block text-sm font-semibold text-[#301934] dark:text-[#F8F6F1]">
                                {{ __('admin.auth.login.username') }}
                            </label>
                            <div class="relative">
                                <input 
                                    id="username" 
                                    name="username" 
                                    type="text" 
                                    autocomplete="username" 
                                    required 
                                    value="{{ old('username') }}"
                                    class="login-input block w-full pl-12 pr-4 h-14 border-2 border-[#E8E0D5]/60 dark:border-[#3A3654]/60 rounded-xl focus:outline-none text-base font-semibold backdrop-blur-sm transition-all duration-300 @error('username') border-red-400 dark:border-red-500 @enderror"
                                    placeholder="{{ __('admin.auth.login.username_placeholder') }}"
                                >
                                <!-- User Icon - INSIDE INPUT -->
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none z-30 bg-transparent">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="7" r="4" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>
                            @error('username')
                                <div class="flex items-center space-x-2 mt-2">
                                    <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-semibold text-[#301934] dark:text-[#F8F6F1]">
                                {{ __('admin.auth.login.password') }}
                            </label>
                            <div class="relative">
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    autocomplete="current-password" 
                                    required 
                                    class="login-input block w-full pl-12 pr-12 h-14 border-2 border-[#E8E0D5]/60 dark:border-[#3A3654]/60 rounded-xl focus:outline-none text-base font-semibold backdrop-blur-sm transition-all duration-300 @error('password') border-red-400 dark:border-red-500 @enderror"
                                    placeholder="{{ __('admin.auth.login.password_placeholder') }}"
                                >
                                <!-- Lock Icon - INSIDE INPUT -->
                                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none z-30 bg-transparent">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <!-- Eye Icon - RIGHT SIDE INSIDE INPUT -->
                                <button 
                                    type="button" 
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 p-1.5 hover:scale-110 transition-transform duration-200 cursor-pointer z-30 bg-transparent rounded-md hover:bg-white/10"
                                    onclick="togglePassword()"
                                >
                                    <svg id="eye-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <circle cx="12" cy="12" r="3" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" style="filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M1 1l22 22" stroke="#CDAF56" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <div class="flex items-center space-x-2 mt-2">
                                    <svg class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">{{ $message }}</p>
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-center">
                            <label class="flex items-center cursor-pointer group">
                                <input id="remember" name="remember" type="checkbox" class="sr-only">
                                <div class="relative">
                                    <div class="w-5 h-5 bg-white/50 dark:bg-[#252340]/50 border-2 border-[#E8E0D5] dark:border-[#3A3654] rounded-md group-hover:border-[#CDAF56] transition-all duration-300"></div>
                                    <svg class="absolute inset-0 w-5 h-5 text-[#CDAF56] opacity-0 transition-opacity duration-300 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <span class="ml-3 text-sm font-medium text-[#4D4052] dark:text-[#D1CBC1]">
                                    {{ __('admin.auth.login.remember_me') }}
                                </span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button 
                                type="submit" 
                                class="w-full flex justify-center py-4 px-6 border border-transparent rounded-xl text-base font-bold text-white geez-gradient hover:shadow-2xl hover:shadow-[#CDAF56]/25 focus:outline-none focus:ring-4 focus:ring-[#CDAF56]/30 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 transform hover:scale-[1.02] active:scale-[0.98]"
                                id="login-button"
                            >
                                <svg class="h-5 w-5 text-white/80 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke-width="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" stroke-width="2"/>
                                </svg>
                                <span id="login-text">{{ __('admin.auth.login.sign_in') }}</span>
                                <svg id="login-spinner" class="hidden animate-spin ml-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Footer -->
                    <div class="mt-10 pt-6 border-t border-[#E8E0D5]/30 dark:border-[#3A3654]/30">
                        <p class="text-center text-xs text-[#6B5B73] dark:text-[#9B8FA3]">
                            {{ __('admin.auth.login.security_notice') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme toggle
        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        }

        // Password toggle
        function togglePassword() {
            const password = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                password.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Form submission
        document.querySelector('form').addEventListener('submit', function() {
            const button = document.getElementById('login-button');
            const text = document.getElementById('login-text');
            const spinner = document.getElementById('login-spinner');
            
            button.disabled = true;
            text.textContent = '{{ __("admin.auth.login.signing_in") }}';
            spinner.classList.remove('hidden');
        });

        // Checkbox
        document.getElementById('remember').addEventListener('change', function() {
            const visual = this.parentElement.querySelector('div');
            const checkmark = visual.querySelector('svg');
            
            if (this.checked) {
                visual.classList.add('border-[#CDAF56]', 'bg-[#CDAF56]/10');
                checkmark.classList.remove('opacity-0');
                checkmark.classList.add('opacity-100');
            } else {
                visual.classList.remove('border-[#CDAF56]', 'bg-[#CDAF56]/10');
                checkmark.classList.remove('opacity-100');
                checkmark.classList.add('opacity-0');
            }
        });

        // Auto-focus
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>
