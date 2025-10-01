/**
 * Geez Logo GSAP Animations
 * 
 * This file contains various animation presets for the Geez logo SVG
 * using GSAP (GreenSock Animation Platform)
 */

import { gsap } from 'gsap';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';
import { MorphSVGPlugin } from 'gsap/MorphSVGPlugin';
import { TextPlugin } from 'gsap/TextPlugin';

// Register GSAP plugins (Note: Some plugins require GSAP membership)
gsap.registerPlugin(DrawSVGPlugin, MorphSVGPlugin, TextPlugin);

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
            drawSVG: "0%",
            opacity: 0
        });
        
        gsap.set(this.logoContainer, {
            scale: 0,
            rotation: -180,
            transformOrigin: "center center"
        });
    }
    
    /**
     * 1. Draw-in Animation - Logo draws itself like being written
     */
    drawInAnimation(duration = 3, delay = 0) {
        const tl = gsap.timeline({ delay });
        
        tl.to(this.logoPath, {
            opacity: 1,
            duration: 0.5,
            ease: "power2.out"
        })
        .to(this.logoPath, {
            drawSVG: "100%",
            duration: duration,
            ease: "power2.inOut"
        }, "-=0.3");
        
        return tl;
    }
    
    /**
     * 2. Fade and Scale Animation - Classic entrance
     */
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
    
    /**
     * 3. Typewriter Effect - Reveals progressively
     */
    typewriterAnimation(duration = 4, delay = 0) {
        const tl = gsap.timeline({ delay });
        
        // Create a mask effect by clipping the path
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
    
    /**
     * 4. Bounce Entrance - Playful bounce effect
     */
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
    
    /**
     * 5. Glow Animation - Adds a glowing effect
     */
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
    
    /**
     * 6. Particle Burst - Creates a burst effect (requires additional elements)
     */
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
    
    /**
     * 7. Morphing Animation - Changes shape (requires MorphSVG plugin)
     */
    morphAnimation(duration = 3, delay = 0) {
        const tl = gsap.timeline({ delay });
        
        // Create a simple shape to morph from
        const simplePath = "M 187.5 50 L 300 150 L 187.5 250 L 75 150 Z";
        
        tl.set(this.logoPath, {
            opacity: 1,
            morphSVG: simplePath
        })
        .to(this.logoPath, {
            morphSVG: this.logoPath.getAttribute('d'),
            duration: duration,
            ease: "power2.inOut"
        });
        
        return tl;
    }
    
    /**
     * 8. Liquid Animation - Fluid, organic movement
     */
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
    
    /**
     * 9. Hover Animation - Interactive hover effect
     */
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
    
    /**
     * 10. Continuous Pulse - Subtle breathing effect
     */
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
    
    /**
     * Helper method to create particles for burst animation
     */
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
    
    /**
     * Master timeline that combines multiple animations
     */
    masterAnimation() {
        const masterTl = gsap.timeline();
        
        masterTl
            .add(this.fadeScaleAnimation(1.5))
            .add(this.glowAnimation(2), "-=0.5")
            .add(() => this.setupHoverAnimation())
            .add(() => this.pulseAnimation());
        
        return masterTl;
    }
    
    /**
     * Random animation selector
     */
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

// Export for use in other modules
export default GeezLogoAnimations;

// Auto-initialize if DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Check if logo exists before initializing
    if (document.querySelector('#geez-logo')) {
        window.geezLogoAnimations = new GeezLogoAnimations();
        
        // Example: Play master animation on load
        // window.geezLogoAnimations.masterAnimation();
    }
});

// Usage examples:
/*
// Basic usage:
const logoAnimations = new GeezLogoAnimations('#geez-logo');

// Play specific animations:
logoAnimations.drawInAnimation(3, 0.5);
logoAnimations.fadeScaleAnimation(2);
logoAnimations.bounceAnimation();

// Chain animations:
const tl = gsap.timeline();
tl.add(logoAnimations.fadeScaleAnimation(1.5))
  .add(logoAnimations.glowAnimation(2), "-=0.5");

// Setup interactive effects:
logoAnimations.setupHoverAnimation();
logoAnimations.pulseAnimation();

// Play random animation:
logoAnimations.playRandomAnimation();
*/
