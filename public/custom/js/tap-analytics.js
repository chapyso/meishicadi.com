/**
 * Tap Analytics JavaScript
 * Records user interactions with business cards
 */

class TapAnalytics {
    constructor(businessId, businessSlug) {
        this.businessId = businessId;
        this.businessSlug = businessSlug;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.recordPageView();
        this.loadTapCount();
        this.setupRealTimeUpdates();
    }

    setupEventListeners() {
        // Record taps on clickable elements
        const clickableElements = document.querySelectorAll('a, button, .clickable, .social-link, .contact-link, .service-item, .appointment-btn, .download-btn, .share-btn');
        
        clickableElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Button', e.target);
            });
        });

        // Record taps on phone numbers
        const phoneElements = document.querySelectorAll('a[href^="tel:"], .phone-number');
        phoneElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Phone', e.target);
            });
        });

        // Record taps on email addresses
        const emailElements = document.querySelectorAll('a[href^="mailto:"], .email-link');
        emailElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Email', e.target);
            });
        });

        // Record taps on social media links
        const socialElements = document.querySelectorAll('a[href*="facebook"], a[href*="twitter"], a[href*="instagram"], a[href*="linkedin"], a[href*="youtube"], .social-media-link');
        socialElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Social', e.target);
            });
        });

        // Record taps on website links
        const websiteElements = document.querySelectorAll('a[href^="http"], .website-link');
        websiteElements.forEach(element => {
            if (!element.href.includes('facebook') && !element.href.includes('twitter') && 
                !element.href.includes('instagram') && !element.href.includes('linkedin') && 
                !element.href.includes('youtube')) {
                element.addEventListener('click', (e) => {
                    this.recordTap('Website', e.target);
                });
            }
        });

        // Record taps on location/map
        const locationElements = document.querySelectorAll('.location-link, .map-link, .address-link');
        locationElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Location', e.target);
            });
        });

        // Record taps on QR code
        const qrElements = document.querySelectorAll('.qr-code, .qr-image');
        qrElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('QR', e.target);
            });
        });

        // Record taps on appointment booking
        const appointmentElements = document.querySelectorAll('.appointment-form, .booking-form, .schedule-btn');
        appointmentElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Appointment', e.target);
            });
        });

        // Record taps on services
        const serviceElements = document.querySelectorAll('.service-card, .service-item, .service-details');
        serviceElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Service', e.target);
            });
        });

        // Record taps on testimonials
        const testimonialElements = document.querySelectorAll('.testimonial-card, .testimonial-item');
        testimonialElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Testimonial', e.target);
            });
        });

        // Record taps on gallery images
        const galleryElements = document.querySelectorAll('.gallery-item, .gallery-image, .photo-item');
        galleryElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Gallery', e.target);
            });
        });

        // Record taps on download actions
        const downloadElements = document.querySelectorAll('.download-vcard, .save-contact, .download-btn');
        downloadElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Download', e.target);
            });
        });

        // Record taps on share actions
        const shareElements = document.querySelectorAll('.share-btn, .share-card, .social-share');
        shareElements.forEach(element => {
            element.addEventListener('click', (e) => {
                this.recordTap('Share', e.target);
            });
        });
    }

    recordPageView() {
        // Record initial page view
        this.sendTapData('Direct', 'Page View');
    }

    recordTap(source, element) {
        let cardId = this.getElementId(element);
        this.sendTapData(source, cardId);
    }

    getElementId(element) {
        // Try to get meaningful identifier for the element
        if (element.id) {
            return element.id;
        }
        if (element.className) {
            return element.className.split(' ')[0];
        }
        if (element.tagName) {
            return element.tagName.toLowerCase();
        }
        return 'unknown';
    }

    sendTapData(tapSource, cardId) {
        const data = {
            business_id: this.businessId,
            tap_source: tapSource,
            card_id: cardId,
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };

        // Send tap data to server
        fetch('/tap-analytics/record', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data._token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Tap recorded successfully:', data);
            } else {
                console.error('Failed to record tap:', data);
            }
        })
        .catch(error => {
            console.error('Error recording tap:', error);
        });
    }

    loadTapCount() {
        fetch(`/tap-analytics/count/${this.businessId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateTapCountDisplay(data.tap_count);
                }
            })
            .catch(error => {
                console.error('Error loading tap count:', error);
            });
    }

    updateTapCountDisplay(count) {
        // Update any existing tap count displays
        const tapCountElements = document.querySelectorAll('.tap-count-display');
        tapCountElements.forEach(element => {
            element.textContent = count.toLocaleString();
        });

        // Create a floating tap counter if it doesn't exist
        if (!document.getElementById('floating-tap-counter')) {
            this.createFloatingTapCounter(count);
        }
    }

    createFloatingTapCounter(count) {
        const counter = document.createElement('div');
        counter.id = 'floating-tap-counter';
        counter.innerHTML = `
            <div class="tap-counter-badge">
                <i class="fas fa-tap"></i>
                <span class="tap-count-display">${count.toLocaleString()}</span>
                <small>taps</small>
            </div>
        `;
        counter.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        `;
        
        counter.addEventListener('click', () => {
            this.incrementTapCount();
        });

        document.body.appendChild(counter);
    }

    incrementTapCount() {
        const data = {
            business_id: this.businessId,
            tap_source: 'Button',
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };

        fetch('/tap-analytics/increment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data._token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.updateTapCountDisplay(data.new_count);
                this.showTapFeedback();
            }
        })
        .catch(error => {
            console.error('Error incrementing tap count:', error);
        });
    }

    showTapFeedback() {
        const counter = document.getElementById('floating-tap-counter');
        if (counter) {
            counter.style.transform = 'scale(1.1)';
            counter.style.background = 'linear-gradient(45deg, #28a745, #20c997)';
            setTimeout(() => {
                counter.style.transform = 'scale(1)';
                counter.style.background = 'linear-gradient(45deg, #667eea 0%, #764ba2 100%)';
            }, 300);
        }
    }

    setupRealTimeUpdates() {
        // Update tap count every 30 seconds
        setInterval(() => {
            this.loadTapCount();
        }, 30000);
    }

    // Method to manually record taps from other scripts
    static recordManualTap(businessId, source, cardId) {
        const data = {
            business_id: businessId,
            tap_source: source,
            card_id: cardId,
            _token: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        };

        fetch('/tap-analytics/record', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data._token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Manual tap recorded successfully:', data);
            } else {
                console.error('Failed to record manual tap:', data);
            }
        })
        .catch(error => {
            console.error('Error recording manual tap:', error);
        });
    }
}

// Initialize tap analytics when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Get business ID and slug from meta tags or data attributes
    const businessId = document.querySelector('meta[name="business-id"]')?.getAttribute('content') || 
                      document.querySelector('[data-business-id]')?.getAttribute('data-business-id');
    const businessSlug = document.querySelector('meta[name="business-slug"]')?.getAttribute('content') || 
                        document.querySelector('[data-business-slug]')?.getAttribute('data-business-slug');
    
    if (businessId) {
        window.tapAnalytics = new TapAnalytics(businessId, businessSlug);
    }
});

// Export for use in other scripts
window.TapAnalytics = TapAnalytics; 