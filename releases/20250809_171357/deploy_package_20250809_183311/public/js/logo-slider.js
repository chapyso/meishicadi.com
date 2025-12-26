/**
 * Dynamic Logo Slider
 * Fetches and displays actual business logos from user profiles
 */

class LogoSlider {
    constructor(containerSelector, options = {}) {
        this.container = document.querySelector(containerSelector);
        this.options = {
            apiEndpoint: '/api/logo-slider/public',
            autoPlay: true,
            speed: 3000,
            showFallback: true,
            maxLogos: 12,
            ...options
        };
        
        this.logos = [];
        this.currentIndex = 0;
        this.isPlaying = false;
        this.interval = null;
        
        this.init();
    }

    async init() {
        if (!this.container) {
            console.error('Logo slider container not found:', this.containerSelector);
            return;
        }

        await this.loadLogos();
        this.render();
        
        if (this.options.autoPlay) {
            this.startAutoPlay();
        }
    }

    async loadLogos() {
        try {
            const response = await fetch(this.options.apiEndpoint);
            const data = await response.json();
            
            if (data.success && data.data) {
                this.logos = data.data.slice(0, this.options.maxLogos);
            } else {
                console.warn('Failed to load logos:', data.message);
                this.logos = this.getDefaultLogos();
            }
        } catch (error) {
            console.error('Error loading logos:', error);
            this.logos = this.getDefaultLogos();
        }
    }

    render() {
        if (!this.logos.length) {
            this.container.innerHTML = '<div class="no-logos">No logos available</div>';
            return;
        }

        // Create the slider structure
        this.container.innerHTML = `
            <div class="logo-slider-track">
                ${this.logos.map(logo => this.createLogoElement(logo)).join('')}
                ${this.logos.map(logo => this.createLogoElement(logo)).join('')} <!-- Duplicate for seamless loop -->
            </div>
        `;

        // Add event listeners
        this.addEventListeners();
    }

    createLogoElement(logo) {
        const isDefault = logo.is_default || false;
        const fallbackClass = isDefault ? 'logo-fallback' : '';
        
        return `
            <div class="logo-slide" data-logo-id="${logo.id}">
                <div class="client-logo ${fallbackClass}">
                    ${this.createLogoContent(logo)}
                </div>
            </div>
        `;
    }

    createLogoContent(logo) {
        if (logo.logo_url && !logo.is_default) {
            return `
                <img src="${logo.logo_url}" 
                     alt="${logo.business_name}" 
                     title="${logo.business_name}"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display: none;">
                    ${logo.fallback_text}
                </div>
            `;
        } else {
            return `
                <div class="logo-placeholder">
                    ${logo.fallback_text}
                </div>
            `;
        }
    }

    addEventListeners() {
        // Pause on hover
        this.container.addEventListener('mouseenter', () => {
            if (this.options.autoPlay) {
                this.pauseAutoPlay();
            }
        });

        this.container.addEventListener('mouseleave', () => {
            if (this.options.autoPlay) {
                this.startAutoPlay();
            }
        });

        // Click to navigate
        this.container.addEventListener('click', (e) => {
            const logoSlide = e.target.closest('.logo-slide');
            if (logoSlide) {
                const logoId = logoSlide.dataset.logoId;
                this.handleLogoClick(logoId);
            }
        });
    }

    handleLogoClick(logoId) {
        const logo = this.logos.find(l => l.id === logoId);
        if (logo && !logo.is_default) {
            // You can add navigation logic here
            console.log('Logo clicked:', logo);
            
            // Example: Navigate to business page
            // window.location.href = `/business/${logo.id}`;
        }
    }

    startAutoPlay() {
        if (this.isPlaying) return;
        
        this.isPlaying = true;
        this.interval = setInterval(() => {
            this.next();
        }, this.options.speed);
    }

    pauseAutoPlay() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
        this.isPlaying = false;
    }

    next() {
        const track = this.container.querySelector('.logo-slider-track');
        if (track) {
            track.style.transform = `translateX(-${this.currentIndex * 100}%)`;
            this.currentIndex++;
            
            // Reset to beginning for seamless loop
            if (this.currentIndex >= this.logos.length) {
                setTimeout(() => {
                    track.style.transition = 'none';
                    this.currentIndex = 0;
                    track.style.transform = 'translateX(0)';
                    setTimeout(() => {
                        track.style.transition = 'transform 0.5s ease-in-out';
                    }, 10);
                }, 500);
            }
        }
    }

    getDefaultLogos() {
        return [
            {
                id: 'default-1',
                title: 'TechCorp',
                business_name: 'TechCorp',
                fallback_text: 'TC',
                is_default: true
            },
            {
                id: 'default-2',
                title: 'InnovateLab',
                business_name: 'InnovateLab',
                fallback_text: 'IL',
                is_default: true
            },
            {
                id: 'default-3',
                title: 'GlobalSoft',
                business_name: 'GlobalSoft',
                fallback_text: 'GS',
                is_default: true
            },
            {
                id: 'default-4',
                title: 'DataFlow',
                business_name: 'DataFlow',
                fallback_text: 'DF',
                is_default: true
            },
            {
                id: 'default-5',
                title: 'CloudTech',
                business_name: 'CloudTech',
                fallback_text: 'CT',
                is_default: true
            },
            {
                id: 'default-6',
                title: 'SmartBiz',
                business_name: 'SmartBiz',
                fallback_text: 'SB',
                is_default: true
            }
        ];
    }

    // Public methods
    refresh() {
        this.loadLogos().then(() => {
            this.render();
        });
    }

    destroy() {
        this.pauseAutoPlay();
        if (this.container) {
            this.container.innerHTML = '';
        }
    }
}

// Initialize logo sliders when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    // Initialize public logo slider
    const publicSlider = document.querySelector('.logo-slider-container');
    if (publicSlider) {
        new LogoSlider('.logo-slider-container', {
            apiEndpoint: '/api/logo-slider/public',
            autoPlay: true,
            speed: 3000
        });
    }

    // Initialize admin logo slider (if user is authenticated)
    const adminSlider = document.querySelector('.admin-logo-slider');
    if (adminSlider && window.isAuthenticated) {
        new LogoSlider('.admin-logo-slider', {
            apiEndpoint: '/api/logo-slider/admin',
            autoPlay: true,
            speed: 4000
        });
    }
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = LogoSlider;
} 