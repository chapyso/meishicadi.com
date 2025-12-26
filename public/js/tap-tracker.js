/**
 * Tap Tracker for MeishiCard Business Cards
 * Tracks when users tap/visit business cards
 */

class TapTracker {
    constructor() {
        this.currentSlug = null;
        this.tracked = false;
        this.init();
    }

    init() {
        // Get the current business slug from the URL
        this.currentSlug = this.getSlugFromUrl();
        
        if (this.currentSlug) {
            // Track the tap after a short delay to ensure page is loaded
            setTimeout(() => {
                this.trackTap();
            }, 1000);
        }
    }

    getSlugFromUrl() {
        const path = window.location.pathname;
        const segments = path.split('/').filter(segment => segment.length > 0);
        
        // The slug should be the first segment after the domain
        return segments[0] || null;
    }

    async trackTap() {
        if (!this.currentSlug || this.tracked) {
            return;
        }

        try {
            // Detect QR scan from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const isQrScan = urlParams.get('qr_scan') === 'true' || 
                           urlParams.get('utm_source') === 'qr' ||
                           this.detectQrScanFromUserAgent();

            // Prepare request data
            const requestData = {};
            if (isQrScan) {
                requestData.qr_scan = 'true';
            }

            const response = await fetch(`/tap/${this.currentSlug}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                },
                body: JSON.stringify(requestData)
            });

            if (response.ok) {
                this.tracked = true;
                const data = await response.json();
                console.log('Tap tracked successfully', data);
                
                // Update tap count display if it exists on the page
                this.updateTapCountDisplay();
            }
        } catch (error) {
            console.error('Error tracking tap:', error);
        }
    }

    /**
     * Detect QR scan from user agent
     */
    detectQrScanFromUserAgent() {
        const userAgent = navigator.userAgent.toLowerCase();
        return userAgent.includes('qr') || 
               userAgent.includes('scanner') || 
               userAgent.includes('camera');
    }

    async updateTapCountDisplay() {
        try {
            const response = await fetch(`/tap/${this.currentSlug}/count`);
            if (response.ok) {
                const data = await response.json();
                
                // Update any tap count displays on the page
                const tapCountElements = document.querySelectorAll('.tap-count-display');
                tapCountElements.forEach(element => {
                    element.textContent = data.formatted_tap_count || data.tap_count || 0;
                });
            }
        } catch (error) {
            console.error('Error updating tap count display:', error);
        }
    }

    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    // Method to manually track a tap (for specific actions)
    async trackManualTap(slug = null) {
        const targetSlug = slug || this.currentSlug;
        if (!targetSlug) return;

        try {
            const response = await fetch(`/tap/${targetSlug}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCsrfToken()
                }
            });

            if (response.ok) {
                console.log('Manual tap tracked successfully');
                return true;
            }
        } catch (error) {
            console.error('Error tracking manual tap:', error);
        }
        return false;
    }
}

// Initialize tap tracker when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.tapTracker = new TapTracker();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TapTracker;
} 