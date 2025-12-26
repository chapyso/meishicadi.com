/**
 * Tap Analytics Tracking Script
 * Automatically tracks user interactions with business cards
 */

class TapAnalytics {
    constructor() {
        this.init();
    }

    init() {
        // Track QR code scans
        this.trackQRScans();
        
        // Track direct link clicks
        this.trackDirectClicks();
        
        // Track NFC interactions (if supported)
        this.trackNFCInteractions();
        
        // Track page views as direct taps
        this.trackPageViews();
    }

    /**
     * Track QR code scans
     */
    trackQRScans() {
        // Listen for QR code scan events
        document.addEventListener('qrScanned', (event) => {
            this.recordTap({
                tap_source: 'QR',
                card_id: event.detail.cardId || null
            });
        });

        // Also track when users access via QR code URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('source') === 'qr') {
            this.recordTap({
                tap_source: 'QR',
                card_id: urlParams.get('card_id') || null
            });
        }
    }

    /**
     * Track direct link clicks
     */
    trackDirectClicks() {
        // Track clicks on business card links
        document.addEventListener('click', (event) => {
            const target = event.target.closest('a');
            if (target && target.href && target.href.includes('/business/')) {
                this.recordTap({
                    tap_source: 'Link',
                    card_id: this.extractCardIdFromUrl(target.href)
                });
            }
        });
    }

    /**
     * Track NFC interactions
     */
    trackNFCInteractions() {
        // Check if Web NFC API is available
        if ('NDEFReader' in window) {
            const ndef = new NDEFReader();
            
            ndef.addEventListener('reading', (event) => {
                this.recordTap({
                    tap_source: 'NFC',
                    card_id: this.extractCardIdFromNFCData(event)
                });
            });

            ndef.scan().catch(error => {
                console.log('NFC not supported or permission denied');
            });
        }

        // Also track NFC via URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('source') === 'nfc') {
            this.recordTap({
                tap_source: 'NFC',
                card_id: urlParams.get('card_id') || null
            });
        }
    }

    /**
     * Track page views as direct taps
     */
    trackPageViews() {
        // Only track if this is a business card page
        if (window.location.pathname.includes('/business/')) {
            // Add a small delay to ensure the page is fully loaded
            setTimeout(() => {
                this.recordTap({
                    tap_source: 'Direct',
                    card_id: this.extractCardIdFromUrl(window.location.href)
                });
            }, 1000);
        }
    }

    /**
     * Record a tap interaction
     */
    recordTap(data) {
        // Get business ID from the current page
        const businessId = this.getBusinessId();
        
        if (!businessId) {
            console.warn('Business ID not found for tap tracking');
            return;
        }

        const tapData = {
            business_id: businessId,
            tap_source: data.tap_source,
            card_id: data.card_id,
            utm_source: this.getUTMParameter('utm_source'),
            utm_medium: this.getUTMParameter('utm_medium'),
            utm_campaign: this.getUTMParameter('utm_campaign')
        };

        // Send the tap data to the server
        fetch('/tap-analytics/record', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.getCSRFToken()
            },
            body: JSON.stringify(tapData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Tap recorded successfully:', data.tap_id);
            } else {
                console.error('Failed to record tap:', data);
            }
        })
        .catch(error => {
            console.error('Error recording tap:', error);
        });
    }

    /**
     * Get business ID from the current page
     */
    getBusinessId() {
        // Try to get from meta tag
        const metaTag = document.querySelector('meta[name="business-id"]');
        if (metaTag) {
            return metaTag.getAttribute('content');
        }

        // Try to get from data attribute
        const businessElement = document.querySelector('[data-business-id]');
        if (businessElement) {
            return businessElement.getAttribute('data-business-id');
        }

        // Try to extract from URL
        const pathParts = window.location.pathname.split('/');
        const businessIndex = pathParts.indexOf('business');
        if (businessIndex !== -1 && pathParts[businessIndex + 1]) {
            // This would need to be resolved to actual business ID
            return this.resolveBusinessIdFromSlug(pathParts[businessIndex + 1]);
        }

        return null;
    }

    /**
     * Resolve business ID from slug
     */
    resolveBusinessIdFromSlug(slug) {
        // This would typically make an API call to resolve slug to ID
        // For now, we'll try to get it from a global variable or data attribute
        if (window.businessData && window.businessData.id) {
            return window.businessData.id;
        }

        // Check if there's a script tag with business data
        const scriptTag = document.querySelector('script[data-business-slug="' + slug + '"]');
        if (scriptTag) {
            try {
                const data = JSON.parse(scriptTag.textContent);
                return data.id;
            } catch (e) {
                console.error('Error parsing business data:', e);
            }
        }

        return null;
    }

    /**
     * Extract card ID from URL
     */
    extractCardIdFromUrl(url) {
        const urlParams = new URLSearchParams(new URL(url).search);
        return urlParams.get('card_id') || null;
    }

    /**
     * Extract card ID from NFC data
     */
    extractCardIdFromNFCData(event) {
        // Parse NFC data to extract card ID
        // This would depend on how the NFC data is formatted
        try {
            const decoder = new TextDecoder();
            const data = decoder.decode(event.message.records[0].data);
            const parsed = JSON.parse(data);
            return parsed.card_id || null;
        } catch (e) {
            console.error('Error parsing NFC data:', e);
            return null;
        }
    }

    /**
     * Get UTM parameter value
     */
    getUTMParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name) || null;
    }

    /**
     * Get CSRF token
     */
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    /**
     * Manually trigger a tap event
     */
    static triggerTap(source, cardId = null) {
        const analytics = new TapAnalytics();
        analytics.recordTap({
            tap_source: source,
            card_id: cardId
        });
    }
}

// Initialize tap analytics when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.tapAnalytics = new TapAnalytics();
});

// Export for use in other scripts
window.TapAnalytics = TapAnalytics; 