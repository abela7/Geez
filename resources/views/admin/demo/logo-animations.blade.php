@extends('layouts.admin')

@section('title', __('admin.demo.logo_animations.title'))

@push('styles')
    @vite(['resources/css/admin/geez-logo-animations.css'])
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                {{ __('admin.demo.logo_animations.title') }}
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                {{ __('admin.demo.logo_animations.description') }}
            </p>
        </div>

        <!-- Logo Display Area -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-12 mb-12">
            <div class="flex justify-center items-center min-h-[400px]">
                <div class="geez-logo-container">
                    @include('components.geez-logo-svg')
                </div>
            </div>
        </div>

        <!-- Animation Controls -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            
            <!-- Basic Animations -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('admin.demo.logo_animations.basic_animations') }}
                </h3>
                <div class="space-y-3">
                    <button onclick="playAnimation('drawIn')" 
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.draw_in') }}
                    </button>
                    <button onclick="playAnimation('fadeScale')" 
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.fade_scale') }}
                    </button>
                    <button onclick="playAnimation('bounce')" 
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.bounce') }}
                    </button>
                </div>
            </div>

            <!-- Creative Animations -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('admin.demo.logo_animations.creative_animations') }}
                </h3>
                <div class="space-y-3">
                    <button onclick="playAnimation('typewriter')" 
                            class="w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.typewriter') }}
                    </button>
                    <button onclick="playAnimation('characterByCharacter')" 
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors">
                        Character by Character
                    </button>
                    <button onclick="playAnimation('glow')" 
                            class="w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.glow') }}
                    </button>
                    <button onclick="playAnimation('liquid')" 
                            class="w-full px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.liquid') }}
                    </button>
                </div>
            </div>

            <!-- Interactive Effects -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    {{ __('admin.demo.logo_animations.interactive_effects') }}
                </h3>
                <div class="space-y-3">
                    <button onclick="toggleHover()" 
                            class="w-full px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.toggle_hover') }}
                    </button>
                    <button onclick="togglePulse()" 
                            class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.toggle_pulse') }}
                    </button>
                    <button onclick="playAnimation('particleBurst')" 
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        {{ __('admin.demo.logo_animations.particle_burst') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Master Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-12">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('admin.demo.logo_animations.master_controls') }}
            </h3>
            <div class="flex flex-wrap gap-4 justify-center">
                <button onclick="playMasterAnimation()" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105">
                    {{ __('admin.demo.logo_animations.play_master') }}
                </button>
                <button onclick="playRandomAnimation()" 
                        class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105">
                    {{ __('admin.demo.logo_animations.play_random') }}
                </button>
                <button onclick="resetLogo()" 
                        class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-semibold transition-all transform hover:scale-105">
                    {{ __('admin.demo.logo_animations.reset') }}
                </button>
            </div>
        </div>

        <!-- Animation Settings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('admin.demo.logo_animations.settings') }}
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('admin.demo.logo_animations.duration') }}
                    </label>
                    <input type="range" id="duration" min="0.5" max="5" step="0.1" value="2" 
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span id="duration-value">2.0</span>s
                    </div>
                </div>
                <div>
                    <label for="delay" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('admin.demo.logo_animations.delay') }}
                    </label>
                    <input type="range" id="delay" min="0" max="3" step="0.1" value="0" 
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span id="delay-value">0.0</span>s
                    </div>
                </div>
                <div>
                    <label for="easing" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('admin.demo.logo_animations.easing') }}
                    </label>
                    <select id="easing" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="power2.out">Power2 Out</option>
                        <option value="power2.inOut">Power2 InOut</option>
                        <option value="back.out(1.7)">Back Out</option>
                        <option value="elastic.out(1, 0.5)">Elastic Out</option>
                        <option value="bounce.out">Bounce Out</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Code Examples -->
        <div class="mt-12 bg-gray-900 rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-white mb-4">
                {{ __('admin.demo.logo_animations.code_examples') }}
            </h3>
            <div class="bg-gray-800 rounded-lg p-4 overflow-x-auto">
                <pre class="text-green-400 text-sm"><code id="code-example">// Initialize the logo animations
const logoAnimations = new GeezLogoAnimations('#geez-logo');

// Play a specific animation
logoAnimations.fadeScaleAnimation(2, 0.5);

// Setup interactive effects
logoAnimations.setupHoverAnimation();
logoAnimations.pulseAnimation();</code></pre>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
    <!-- GSAP CDN for immediate availability -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    
    <script>
        // Global variables
        let logoAnimations;
        let hoverEnabled = false;
        let pulseEnabled = false;

        // Simple GSAP Animation Class (inline for demo)
        class GeezLogoAnimations {
            constructor(selector = '#geez-logo') {
                this.logo = document.querySelector(selector);
                this.logoPath = document.querySelector('#logo-path');
                this.logoContainer = document.querySelector('#logo-container');
                
                if (!this.logo) {
                    console.warn('Geez logo not found. Make sure the SVG is loaded with the correct ID.');
                    return;
                }
                
                this.init();
            }
            
            init() {
                // Set initial states
                gsap.set(this.logoPath, {
                    opacity: 0
                });
                
                gsap.set(this.logoContainer, {
                    scale: 0,
                    rotation: -180,
                    transformOrigin: "center center"
                });
            }
            
            drawInAnimation(duration = 3, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                // Character-by-character reveal using clip-path
                tl.set(this.logoPath, {
                    opacity: 1,
                    clipPath: "inset(0 100% 0 0)"
                })
                .to(this.logoPath, {
                    clipPath: "inset(0 0% 0 0)",
                    duration: duration,
                    ease: "power2.inOut"
                })
                .to(this.logoContainer, {
                    scale: 1,
                    rotation: 0,
                    duration: 0.5,
                    ease: "power2.out"
                }, 0);
                
                return tl;
            }
            
            characterByCharacterAnimation(duration = 4, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                // Create multiple copies of the path for character effect
                this.createCharacterMasks();
                
                tl.set(this.logoPath, { opacity: 1 })
                .set('.char-mask', { 
                    opacity: 0,
                    scaleX: 0,
                    transformOrigin: "left center"
                })
                .to('.char-mask', {
                    opacity: 1,
                    scaleX: 1,
                    duration: duration / 6,
                    stagger: duration / 6,
                    ease: "power2.out"
                });
                
                return tl;
            }
            
            typewriterAnimation(duration = 4, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                // Simulate typewriter by revealing sections progressively
                const sections = 8; // Divide logo into sections
                
                tl.set(this.logoPath, {
                    opacity: 1,
                    clipPath: "inset(0 100% 0 0)"
                });
                
                // Animate each section
                for (let i = 0; i < sections; i++) {
                    const startPercent = (i / sections) * 100;
                    const endPercent = ((i + 1) / sections) * 100;
                    
                    tl.to(this.logoPath, {
                        clipPath: `inset(0 ${100 - endPercent}% 0 0)`,
                        duration: duration / sections,
                        ease: "power1.inOut"
                    }, i * (duration / sections));
                }
                
                return tl;
            }
            
            fadeScaleAnimation(duration = 2, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                tl.to(this.logoContainer, {
                    scale: 1,
                    rotation: 0,
                    duration: duration,
                    ease: "back.out(1.7)"
                })
                .to(this.logoPath, {
                    opacity: 1,
                    duration: duration * 0.6,
                    ease: "power2.out"
                }, "-=1.2");
                
                return tl;
            }
            
            bounceAnimation(duration = 2.5, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                tl.set(this.logoContainer, {
                    y: -100,
                    scale: 0.8
                })
                .set(this.logoPath, {
                    opacity: 1
                })
                .to(this.logoContainer, {
                    y: 0,
                    scale: 1,
                    duration: duration,
                    ease: "bounce.out"
                });
                
                return tl;
            }
            
            typewriterAnimation(duration = 4, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                tl.set(this.logoPath, {
                    opacity: 1,
                    clipPath: "inset(0 100% 0 0)"
                })
                .to(this.logoPath, {
                    clipPath: "inset(0 0% 0 0)",
                    duration: duration,
                    ease: "power2.inOut"
                });
                
                return tl;
            }
            
            glowAnimation(duration = 2, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                tl.set(this.logoPath, {
                    opacity: 1,
                    filter: "drop-shadow(0 0 0px #e3c56b)"
                })
                .to(this.logoPath, {
                    filter: "drop-shadow(0 0 20px #e3c56b)",
                    duration: duration / 2,
                    ease: "power2.inOut"
                })
                .to(this.logoPath, {
                    filter: "drop-shadow(0 0 5px #e3c56b)",
                    duration: duration / 2,
                    ease: "power2.inOut"
                });
                
                return tl;
            }
            
            liquidAnimation(duration = 4, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                tl.set(this.logoPath, { opacity: 1 })
                .from(this.logoContainer, {
                    scaleY: 0,
                    transformOrigin: "bottom center",
                    duration: duration * 0.6,
                    ease: "elastic.out(1, 0.5)"
                })
                .from(this.logoContainer, {
                    scaleX: 0.5,
                    duration: duration * 0.4,
                    ease: "elastic.out(1, 0.3)"
                }, "-=0.5");
                
                return tl;
            }
            
            particleBurstAnimation(duration = 3, delay = 0) {
                const tl = gsap.timeline({ delay });
                
                // Create particles around the logo
                this.createParticles();
                
                tl.set(this.logoPath, { opacity: 1 })
                .from('.logo-particle', {
                    scale: 0,
                    opacity: 0,
                    duration: 0.5,
                    stagger: 0.1,
                    ease: "back.out(1.7)"
                })
                .to('.logo-particle', {
                    x: "random(-200, 200)",
                    y: "random(-200, 200)",
                    opacity: 0,
                    scale: 0,
                    duration: duration - 0.5,
                    stagger: 0.05,
                    ease: "power2.out"
                }, "-=0.3");
                
                return tl;
            }
            
            setupHoverAnimation() {
                if (!this.logo) return;
                
                const hoverTl = gsap.timeline({ paused: true });
                
                hoverTl.to(this.logoContainer, {
                    scale: 1.1,
                    rotation: 5,
                    duration: 0.3,
                    ease: "power2.out"
                })
                .to(this.logoPath, {
                    fill: "#f4d03f",
                    filter: "drop-shadow(0 0 15px #e3c56b)",
                    duration: 0.3,
                    ease: "power2.out"
                }, 0);
                
                this.logo.addEventListener('mouseenter', () => hoverTl.play());
                this.logo.addEventListener('mouseleave', () => hoverTl.reverse());
            }
            
            pulseAnimation() {
                if (!this.logoPath) return;
                
                gsap.to(this.logoPath, {
                    filter: "drop-shadow(0 0 10px #e3c56b)",
                    duration: 2,
                    ease: "power2.inOut",
                    yoyo: true,
                    repeat: -1
                });
            }
            
            createParticles() {
                const particleCount = 20;
                const logoRect = this.logo.getBoundingClientRect();
                const centerX = logoRect.left + logoRect.width / 2;
                const centerY = logoRect.top + logoRect.height / 2;
                
                for (let i = 0; i < particleCount; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'logo-particle';
                    particle.style.cssText = `
                        position: fixed;
                        width: 4px;
                        height: 4px;
                        background: #e3c56b;
                        border-radius: 50%;
                        left: ${centerX}px;
                        top: ${centerY}px;
                        pointer-events: none;
                        z-index: 1000;
                    `;
                    document.body.appendChild(particle);
                }
                
                // Clean up particles after animation
                setTimeout(() => {
                    document.querySelectorAll('.logo-particle').forEach(p => p.remove());
                }, 5000);
            }
            
            createCharacterMasks() {
                // Remove existing masks
                document.querySelectorAll('.char-mask').forEach(el => el.remove());
                
                const logoContainer = this.logoContainer;
                const pathData = this.logoPath.getAttribute('d');
                
                // Create 6 character sections (simulating individual characters)
                for (let i = 0; i < 6; i++) {
                    const mask = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    mask.setAttribute('d', pathData);
                    mask.setAttribute('fill', '#e3c56b');
                    mask.setAttribute('class', 'char-mask');
                    mask.style.clipPath = `inset(0 ${100 - (i + 1) * 16.67}% 0 ${i * 16.67}%)`;
                    
                    logoContainer.appendChild(mask);
                }
            }
            
            masterAnimation() {
                const masterTl = gsap.timeline();
                
                masterTl
                    .add(this.fadeScaleAnimation(1.5))
                    .add(this.glowAnimation(2), "-=0.5")
                    .add(() => this.setupHoverAnimation())
                    .add(() => this.pulseAnimation());
                
                return masterTl;
            }
            
            playRandomAnimation() {
                const animations = [
                    () => this.drawInAnimation(),
                    () => this.fadeScaleAnimation(),
                    () => this.typewriterAnimation(),
                    () => this.bounceAnimation(),
                    () => this.glowAnimation(),
                    () => this.liquidAnimation()
                ];
                
                const randomAnimation = animations[Math.floor(Math.random() * animations.length)];
                return randomAnimation();
            }
        }

        // Robust init that works whether DOMContentLoaded already fired or not
        function initLogoDemo() {
            logoAnimations = new GeezLogoAnimations('#geez-logo');
            console.log('Logo animations initialized');
            setupRangeInputs();
            // Auto-play a default entrance so the canvas isn't blank
            setTimeout(() => {
                if (logoAnimations) {
                    logoAnimations.fadeScaleAnimation(1.5, 0);
                }
            }, 100);
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLogoDemo);
        } else {
            initLogoDemo();
        }

        // Animation control functions
        window.playAnimation = function(type) {
            if (!logoAnimations) return;

            const duration = parseFloat(document.getElementById('duration').value);
            const delay = parseFloat(document.getElementById('delay').value);

            resetLogo();

            setTimeout(() => {
                switch(type) {
                    case 'drawIn':
                        logoAnimations.drawInAnimation(duration, delay);
                        updateCodeExample('drawInAnimation', duration, delay);
                        break;
                    case 'fadeScale':
                        logoAnimations.fadeScaleAnimation(duration, delay);
                        updateCodeExample('fadeScaleAnimation', duration, delay);
                        break;
                    case 'bounce':
                        logoAnimations.bounceAnimation(duration, delay);
                        updateCodeExample('bounceAnimation', duration, delay);
                        break;
                    case 'typewriter':
                        logoAnimations.typewriterAnimation(duration, delay);
                        updateCodeExample('typewriterAnimation', duration, delay);
                        break;
                    case 'characterByCharacter':
                        logoAnimations.characterByCharacterAnimation(duration, delay);
                        updateCodeExample('characterByCharacterAnimation', duration, delay);
                        break;
                    case 'glow':
                        logoAnimations.glowAnimation(duration, delay);
                        updateCodeExample('glowAnimation', duration, delay);
                        break;
                    case 'liquid':
                        logoAnimations.liquidAnimation(duration, delay);
                        updateCodeExample('liquidAnimation', duration, delay);
                        break;
                    case 'particleBurst':
                        logoAnimations.particleBurstAnimation(duration, delay);
                        updateCodeExample('particleBurstAnimation', duration, delay);
                        break;
                }
            }, 100);
        }

        window.playMasterAnimation = function() {
            if (!logoAnimations) return;
            resetLogo();
            setTimeout(() => {
                logoAnimations.masterAnimation();
                updateCodeExample('masterAnimation');
            }, 100);
        }

        window.playRandomAnimation = function() {
            if (!logoAnimations) return;
            resetLogo();
            setTimeout(() => {
                logoAnimations.playRandomAnimation();
                updateCodeExample('playRandomAnimation');
            }, 100);
        }

        window.toggleHover = function() {
            if (!logoAnimations) return;
            
            if (hoverEnabled) {
                // Remove hover listeners (simplified)
                hoverEnabled = false;
                console.log('Hover disabled');
            } else {
                logoAnimations.setupHoverAnimation();
                hoverEnabled = true;
                console.log('Hover enabled');
            }
            updateCodeExample('setupHoverAnimation');
        }

        window.togglePulse = function() {
            if (!logoAnimations) return;
            
            if (pulseEnabled) {
                // Stop pulse (would need to store timeline reference)
                pulseEnabled = false;
                console.log('Pulse disabled');
            } else {
                logoAnimations.pulseAnimation();
                pulseEnabled = true;
                console.log('Pulse enabled');
            }
            updateCodeExample('pulseAnimation');
        }

        window.resetLogo = function() {
            if (!logoAnimations) return;
            logoAnimations.init();
        }

        function setupRangeInputs() {
            const durationSlider = document.getElementById('duration');
            const delaySlider = document.getElementById('delay');
            const durationValue = document.getElementById('duration-value');
            const delayValue = document.getElementById('delay-value');

            if (durationSlider && durationValue) {
                durationSlider.addEventListener('input', function() {
                    durationValue.textContent = parseFloat(this.value).toFixed(1);
                });
            }

            if (delaySlider && delayValue) {
                delaySlider.addEventListener('input', function() {
                    delayValue.textContent = parseFloat(this.value).toFixed(1);
                });
            }
        }

        function updateCodeExample(method, duration = null, delay = null) {
            const codeElement = document.getElementById('code-example');
            if (!codeElement) return;
            
            let code = `// ${method}\nconst logoAnimations = new GeezLogoAnimations('#geez-logo');\n`;
            
            if (duration !== null && delay !== null) {
                code += `logoAnimations.${method}(${duration}, ${delay});`;
            } else {
                code += `logoAnimations.${method}();`;
            }

            codeElement.textContent = code;
        }

        function playMasterAnimation() {
            if (!logoAnimations) return;
            resetLogo();
            setTimeout(() => {
                logoAnimations.masterAnimation();
                updateCodeExample('masterAnimation');
            }, 100);
        }

        function playRandomAnimation() {
            if (!logoAnimations) return;
            resetLogo();
            setTimeout(() => {
                logoAnimations.playRandomAnimation();
                updateCodeExample('playRandomAnimation');
            }, 100);
        }

        function toggleHover() {
            if (!logoAnimations) return;
            
            if (hoverEnabled) {
                // Remove hover listeners (simplified)
                hoverEnabled = false;
                console.log('Hover disabled');
            } else {
                logoAnimations.setupHoverAnimation();
                hoverEnabled = true;
                console.log('Hover enabled');
            }
            updateCodeExample('setupHoverAnimation');
        }

        function togglePulse() {
            if (!logoAnimations) return;
            
            if (pulseEnabled) {
                // Stop pulse (would need to store timeline reference)
                pulseEnabled = false;
                console.log('Pulse disabled');
            } else {
                logoAnimations.pulseAnimation();
                pulseEnabled = true;
                console.log('Pulse enabled');
            }
            updateCodeExample('pulseAnimation');
        }

        function resetLogo() {
            if (!logoAnimations) return;
            logoAnimations.init();
        }

        function setupRangeInputs() {
            const durationSlider = document.getElementById('duration');
            const delaySlider = document.getElementById('delay');
            const durationValue = document.getElementById('duration-value');
            const delayValue = document.getElementById('delay-value');

            if (durationSlider && durationValue) {
                durationSlider.addEventListener('input', function() {
                    durationValue.textContent = parseFloat(this.value).toFixed(1);
                });
            }

            if (delaySlider && delayValue) {
                delaySlider.addEventListener('input', function() {
                    delayValue.textContent = parseFloat(this.value).toFixed(1);
                });
            }
        }

        function updateCodeExample(method, duration = null, delay = null) {
            const codeElement = document.getElementById('code-example');
            if (!codeElement) return;
            
            let code = `// ${method}\nconst logoAnimations = new GeezLogoAnimations('#geez-logo');\n`;
            
            if (duration !== null && delay !== null) {
                code += `logoAnimations.${method}(${duration}, ${delay});`;
            } else {
                code += `logoAnimations.${method}();`;
            }

            codeElement.textContent = code;
        }
    </script>
@endpush
