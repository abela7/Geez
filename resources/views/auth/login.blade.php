@extends('layouts.app')

@section('title', __('auth.login_title') . ' - ' . config('app.name'))

@section('content')
<!-- Clean Centered Design -->
<div class="min-h-screen flex flex-col bg-cream dark:bg-eggplant">
    <!-- Header with Language & Theme Switchers -->
    <header class="flex justify-end p-6">
        <div class="flex items-center space-x-4 bg-white-token dark:bg-plum-gray rounded-xl px-4 py-3 shadow-lg border border-gold/20">
            <!-- Language Switch -->
            <div class="relative" x-data="{ open: false }">
                <button 
                    @click="open = !open"
                    class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-eggplant dark:text-cream hover:text-gold dark:hover:text-khaki transition-colors focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-white-token dark:focus:ring-offset-plum-gray rounded-lg"
                    :aria-expanded="open"
                    aria-label="{{ __('auth.language_switch') }}"
                >
                    <!-- Globe Icon -->
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 919-9"></path>
                    </svg>
                    <span>EN</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                
                <div 
                    x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white-token dark:bg-eggplant rounded-lg shadow-xl border border-gold/30 focus:outline-none z-50"
                    style="display: none;"
                >
                    <div class="py-2">
                        <a href="{{ route('language.switch', 'en') }}" class="block px-4 py-2 text-sm text-eggplant dark:text-cream hover:bg-gold/10 dark:hover:bg-khaki/10 transition-colors {{ app()->getLocale() == 'en' ? 'bg-gold/20 font-semibold' : '' }}">{{ __('auth.language_english') }}</a>
                        <a href="{{ route('language.switch', 'am') }}" class="block px-4 py-2 text-sm text-eggplant dark:text-cream hover:bg-gold/10 dark:hover:bg-khaki/10 transition-colors font-ethiopic {{ app()->getLocale() == 'am' ? 'bg-gold/20 font-semibold' : '' }}">{{ __('auth.language_amharic') }}</a>
                        <a href="{{ route('language.switch', 'ti') }}" class="block px-4 py-2 text-sm text-eggplant dark:text-cream hover:bg-gold/10 dark:hover:bg-khaki/10 transition-colors font-ethiopic {{ app()->getLocale() == 'ti' ? 'bg-gold/20 font-semibold' : '' }}">{{ __('auth.language_tigrinya') }}</a>
                    </div>
                </div>
            </div>

            <!-- Theme Switch -->
            <button 
                x-data="{ 
                    dark: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                    toggle() {
                        this.dark = !this.dark;
                        localStorage.theme = this.dark ? 'dark' : 'light';
                        document.documentElement.classList.toggle('dark', this.dark);
                    }
                }"
                x-init="document.documentElement.classList.toggle('dark', dark)"
                @click="toggle()"
                class="p-2 text-eggplant dark:text-cream hover:text-gold dark:hover:text-khaki transition-colors focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-white-token dark:focus:ring-offset-plum-gray rounded-lg"
                :aria-label="__('auth.theme_toggle')"
            >
                <!-- Sun Icon (Light Mode) -->
                <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <!-- Moon Icon (Dark Mode) -->
                <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Login Card Container -->
    <div class="flex-1 flex items-center justify-center p-6">
        <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white-token dark:bg-plum-gray rounded-2xl shadow-xl p-8">

            <!-- Coffee Cup Icon -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-cream dark:bg-eggplant rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-eggplant dark:text-cream" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3a2 2 0 002 2h4a2 2 0 002-2v-3M8 14V8a2 2 0 012-2h4a2 2 0 012 2v6M8 14H6a2 2 0 01-2-2V9a2 2 0 012-2h2m0 0V5a2 2 0 012-2h4a2 2 0 012 2v2m0 0h2a2 2 0 012 2v3a2 2 0 01-2 2h-2"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-eggplant dark:text-cream mb-2">Restaurant Admin</h1>
                <p class="text-plum-gray dark:text-khaki text-sm">{{ __('auth.welcome_back') }}</p>
            </div>

            <!-- Login Form -->
            <form 
                x-data="{ 
                    loading: false, 
                    error: '', 
                    success: false,
                    email: '',
                    password: '',
                    emailError: '',
                    passwordError: '',
                    
                    validateEmail() {
                        if (!this.email) {
                            this.emailError = '{{ __('auth.error_required_email') }}';
                            return false;
                        }
                        this.emailError = '';
                        return true;
                    },
                    
                    validatePassword() {
                        if (!this.password) {
                            this.passwordError = '{{ __('auth.error_required_password') }}';
                            return false;
                        }
                        this.passwordError = '';
                        return true;
                    },
                    
                    async submitForm() {
                        this.error = '';
                        
                        if (!this.validateEmail() || !this.validatePassword()) {
                            return;
                        }
                        
                        this.loading = true;
                        
                        // Simulate API call
                        await new Promise(resolve => setTimeout(resolve, 2000));
                        
                        // Simulate error for demo
                        if (this.email !== 'admin@geez.com' || this.password !== 'password') {
                            this.error = '{{ __('auth.error_invalid_credentials') }}';
                            this.loading = false;
                            return;
                        }
                        
                        // Success state
                        this.success = true;
                        setTimeout(() => {
                            // Redirect placeholder
                            console.log('Redirecting to dashboard...');
                        }, 1000);
                    }
                }"
                @submit.prevent="submitForm()"
                class="space-y-6"
            >
                <!-- Success State -->
                <div x-show="success" x-transition class="p-4 bg-khaki/15 border border-khaki rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-khaki mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-eggplant dark:text-cream font-medium">{{ __('auth.success_login') }}</p>
                    </div>
                </div>

                <!-- Error State -->
                <div x-show="error" x-transition class="p-4 bg-gold/15 border border-gold rounded-lg mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gold mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p class="text-eggplant dark:text-cream font-medium" x-text="error"></p>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-eggplant dark:text-cream mb-2">
                        {{ __('auth.email') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-plum-gray dark:text-khaki" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            x-model="email"
                            @blur="validateEmail()"
                            :class="emailError ? 'border-gold focus:border-gold focus:ring-gold' : 'border-gray-300 dark:border-eggplant focus:border-gold focus:ring-gold'"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border bg-white-token dark:bg-eggplant text-eggplant dark:text-cream placeholder-plum-gray dark:placeholder-khaki focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white-token dark:focus:ring-offset-plum-gray transition-colors"
                            placeholder="admin@restaurant.com"
                            required
                            autocomplete="email"
                            :disabled="loading || success"
                        >
                    </div>
                    <p x-show="emailError" x-text="emailError" class="mt-1 text-sm text-gold"></p>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-eggplant dark:text-cream mb-2">
                        {{ __('auth.password') }}
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-plum-gray dark:text-khaki" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            id="password" 
                            name="password"
                            x-model="password"
                            @blur="validatePassword()"
                            :class="passwordError ? 'border-gold focus:border-gold focus:ring-gold' : 'border-gray-300 dark:border-eggplant focus:border-gold focus:ring-gold'"
                            class="w-full pl-10 pr-12 py-3 rounded-lg border bg-white-token dark:bg-eggplant text-eggplant dark:text-cream placeholder-plum-gray dark:placeholder-khaki focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white-token dark:focus:ring-offset-plum-gray transition-colors"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password"
                            :disabled="loading || success"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <svg class="w-5 h-5 text-plum-gray dark:text-khaki" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p x-show="passwordError" x-text="passwordError" class="mt-1 text-sm text-gold"></p>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember"
                            class="w-4 h-4 text-gold border-plum-gray rounded focus:ring-gold focus:ring-2 bg-white-token dark:bg-eggplant"
                            :disabled="loading || success"
                        >
                        <label for="remember" class="ml-2 text-sm text-plum-gray dark:text-khaki">
                            {{ __('auth.remember_me') }}
                        </label>
                    </div>
                    <a 
                        href="#" 
                        class="text-sm text-gold hover:text-eggplant dark:text-khaki dark:hover:text-gold transition-colors focus:outline-none"
                    >
                        {{ __('auth.forgot_password') }}
                    </a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    :disabled="loading || success"
                    :class="loading || success ? 'bg-plum-gray cursor-not-allowed' : 'bg-gold hover:bg-khaki'"
                    class="w-full py-3 px-4 rounded-lg text-white font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-gold focus:ring-offset-2 focus:ring-offset-white-token dark:focus:ring-offset-plum-gray mb-6"
                >
                    <span x-show="!loading">Sign In</span>
                    <span x-show="loading" class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('auth.loading') }}
                    </span>
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center text-xs text-plum-gray dark:text-khaki">
                © 2023 Restaurant Admin. All rights reserved.
            </div>
        </div>
        </div>
    </div>
</div>

<!-- TV Mode Styles (≥1920px) -->
<style>
@media (min-width: 1920px) {
    .max-w-md { max-width: 32rem; }
    input, button { min-height: 3.5rem; font-size: 1.125rem; }
    .text-2xl { font-size: 2.5rem; }
}
</style>
@endsection
