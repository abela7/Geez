# Geez Logo GSAP Animation Guide

This guide explains how to use the GSAP-powered animations for your Geez logo SVG.

## 🚀 Quick Start

### 1. Installation Complete ✅
- GSAP library installed via npm
- Animation files created and configured
- Vite build system updated

### 2. Files Created
```
public/images/geez-logo-animated.svg          # Optimized SVG for animations
resources/js/admin/geez-logo-animations.js   # Main animation class
resources/css/admin/geez-logo-animations.css # Supporting styles
resources/views/components/geez-logo-svg.blade.php # Reusable component
resources/views/admin/demo/logo-animations.blade.php # Demo page
```

## 🎯 Basic Usage

### Include the Logo Component
```php
{{-- In any Blade template --}}
@include('components.geez-logo-svg')

{{-- With custom size --}}
@include('components.geez-logo-svg', ['size' => 'large'])

{{-- With custom class --}}
@include('components.geez-logo-svg', ['class' => 'my-custom-class'])
```

### Initialize Animations
```javascript
// Import and initialize
import GeezLogoAnimations from './geez-logo-animations.js';
const logoAnimations = new GeezLogoAnimations('#geez-logo');

// Play specific animations
logoAnimations.fadeScaleAnimation(2, 0.5); // duration, delay
logoAnimations.drawInAnimation(3);
logoAnimations.bounceAnimation();
```

## 🎨 Available Animations

### 1. Basic Entrance Animations
```javascript
// Fade and scale entrance
logoAnimations.fadeScaleAnimation(duration, delay);

// Draw-in effect (like being written)
logoAnimations.drawInAnimation(duration, delay);

// Bounce entrance
logoAnimations.bounceAnimation(duration, delay);

// Typewriter reveal
logoAnimations.typewriterAnimation(duration, delay);
```

### 2. Creative Effects
```javascript
// Glow effect
logoAnimations.glowAnimation(duration, delay);

// Liquid/fluid animation
logoAnimations.liquidAnimation(duration, delay);

// Particle burst (creates temporary particles)
logoAnimations.particleBurstAnimation(duration, delay);

// Morphing animation (requires MorphSVG plugin)
logoAnimations.morphAnimation(duration, delay);
```

### 3. Interactive Effects
```javascript
// Setup hover interactions
logoAnimations.setupHoverAnimation();

// Continuous pulse effect
logoAnimations.pulseAnimation();

// Master animation (combines multiple effects)
logoAnimations.masterAnimation();

// Random animation
logoAnimations.playRandomAnimation();
```

## 🔧 Customization

### Animation Parameters
```javascript
// All animations accept duration and delay
const duration = 2.5; // seconds
const delay = 0.5;    // seconds

logoAnimations.fadeScaleAnimation(duration, delay);
```

### Custom Easing
```javascript
// Modify the animation class or use GSAP directly
gsap.to('#logo-container', {
    scale: 1,
    duration: 2,
    ease: "elastic.out(1, 0.5)" // Custom easing
});
```

### Color Variations
```css
/* In your CSS */
#logo-path {
    fill: #your-custom-color;
}

/* Dark theme support */
.dark #logo-path {
    fill: #your-dark-theme-color;
}
```

## 🎮 Demo Page

Visit the demo page to test all animations interactively:
```
/admin/demo/logo-animations
```

The demo includes:
- Live animation previews
- Adjustable duration and delay controls
- Easing function selection
- Code examples for each animation
- Interactive controls

## 📱 Responsive Design

The logo automatically adapts to different screen sizes:

```php
{{-- Size options --}}
@include('components.geez-logo-svg', ['size' => 'small'])   {{-- 128x128px --}}
@include('components.geez-logo-svg', ['size' => 'medium'])  {{-- 256x256px --}}
@include('components.geez-logo-svg', ['size' => 'large'])   {{-- 384x384px --}}
@include('components.geez-logo-svg', ['size' => 'xl'])      {{-- 500x500px --}}
```

## 🎯 Integration Examples

### 1. Dashboard Header
```php
{{-- In your dashboard layout --}}
<header class="dashboard-header">
    <div class="logo-container">
        @include('components.geez-logo-svg', ['size' => 'medium'])
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoAnimations = new GeezLogoAnimations('#geez-logo');
    logoAnimations.fadeScaleAnimation(1.5);
    logoAnimations.setupHoverAnimation();
});
</script>
```

### 2. Loading Screen
```php
<div id="loading-screen" class="fixed inset-0 bg-white flex items-center justify-center">
    @include('components.geez-logo-svg', ['size' => 'large'])
</div>

<script>
const logoAnimations = new GeezLogoAnimations('#geez-logo');
logoAnimations.drawInAnimation(3).then(() => {
    // Hide loading screen after animation
    document.getElementById('loading-screen').style.display = 'none';
});
</script>
```

### 3. Page Transitions
```javascript
// On page load
window.addEventListener('load', function() {
    const logoAnimations = new GeezLogoAnimations('#geez-logo');
    logoAnimations.liquidAnimation(2);
});

// On navigation
function navigateWithAnimation() {
    const logoAnimations = new GeezLogoAnimations('#geez-logo');
    logoAnimations.glowAnimation(1).then(() => {
        // Navigate to new page
        window.location.href = '/new-page';
    });
}
```

## 🔧 Advanced Configuration

### Custom Animation Timeline
```javascript
// Create custom animation sequence
const tl = gsap.timeline();
tl.add(logoAnimations.fadeScaleAnimation(1))
  .add(logoAnimations.glowAnimation(2), "-=0.5")
  .add(() => logoAnimations.setupHoverAnimation());
```

### Performance Optimization
```javascript
// For better performance on mobile
if (window.innerWidth < 768) {
    // Use simpler animations on mobile
    logoAnimations.fadeScaleAnimation(1);
} else {
    // Full animations on desktop
    logoAnimations.masterAnimation();
}
```

### Accessibility
The animations respect user preferences:
```css
/* Animations are disabled for users who prefer reduced motion */
@media (prefers-reduced-motion: reduce) {
    /* All animations are automatically disabled */
}
```

## 🎨 Styling Integration

### With Tailwind CSS
```php
<div class="flex items-center justify-center p-8 bg-gradient-to-br from-blue-50 to-indigo-100">
    @include('components.geez-logo-svg', ['size' => 'large', 'class' => 'drop-shadow-lg'])
</div>
```

### Custom CSS Classes
```css
.logo-hero {
    filter: drop-shadow(0 10px 30px rgba(227, 197, 107, 0.3));
    transition: all 0.3s ease;
}

.logo-hero:hover {
    transform: scale(1.05);
}
```

## 🚀 Production Tips

1. **Preload GSAP**: Include GSAP in your main bundle for faster loading
2. **Lazy Load**: Load animations only when needed
3. **Reduce Motion**: Always respect user preferences
4. **Performance**: Use `will-change` CSS property for animated elements
5. **Fallbacks**: Provide CSS-only fallbacks for critical animations

## 🔍 Troubleshooting

### Common Issues

**Animation not working?**
- Check if GSAP is loaded: `console.log(gsap)`
- Verify SVG element exists: `document.querySelector('#geez-logo')`
- Check browser console for errors

**Performance issues?**
- Reduce animation complexity on mobile
- Use `transform` and `opacity` properties for best performance
- Avoid animating `width`, `height`, or `top/left` properties

**SVG not displaying?**
- Ensure the SVG file is accessible
- Check viewBox and dimensions
- Verify CSS is not hiding the element

## 📚 Resources

- [GSAP Documentation](https://greensock.com/docs/)
- [SVG Animation Guide](https://css-tricks.com/guide-svg-animations-smil/)
- [Performance Best Practices](https://web.dev/animations-guide/)

---

**Need help?** Check the demo page at `/admin/demo/logo-animations` for interactive examples and code snippets!
