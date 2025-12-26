/**
 * Enhanced Notification UI JavaScript
 * Provides interactive functionality for notification badges
 */

class NotificationUI {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupTooltips();
        this.setupAccessibility();
    }

    setupEventListeners() {
        // Add click handlers for notification badges
        document.querySelectorAll('.notification-badge').forEach(badge => {
            badge.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.handleNotificationClick(badge);
            });

            // Add keyboard support
            badge.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    this.handleNotificationClick(badge);
                }
            });
        });

        // Add hover effects for menu items with notifications
        document.querySelectorAll('.dash-item').forEach(item => {
            const badge = item.querySelector('.notification-badge');
            if (badge) {
                item.addEventListener('mouseenter', () => {
                    this.enhanceHoverEffect(badge);
                });

                item.addEventListener('mouseleave', () => {
                    this.removeHoverEffect(badge);
                });
            }
        });
    }

    setupTooltips() {
        // Enhanced tooltip functionality
        document.querySelectorAll('.notification-badge[title]').forEach(badge => {
            badge.addEventListener('mouseenter', (e) => {
                this.showEnhancedTooltip(e.target);
            });

            badge.addEventListener('mouseleave', (e) => {
                this.hideEnhancedTooltip(e.target);
            });
        });
    }

    setupAccessibility() {
        // Make notification badges focusable
        document.querySelectorAll('.notification-badge').forEach(badge => {
            badge.setAttribute('tabindex', '0');
            badge.setAttribute('role', 'button');
            badge.setAttribute('aria-label', badge.getAttribute('title') || 'Notification');
        });
    }

    handleNotificationClick(badge) {
        const item = badge.closest('.dash-item');
        const link = item.querySelector('.dash-link');
        
        // Add click animation
        this.addClickAnimation(badge);
        
        // Navigate to the link after animation
        setTimeout(() => {
            if (link && link.href) {
                window.location.href = link.href;
            }
        }, 300);
    }

    addClickAnimation(badge) {
        badge.style.transform = 'scale(0.8)';
        badge.style.transition = 'transform 0.2s ease';
        
        setTimeout(() => {
            badge.style.transform = 'scale(1.2)';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
                badge.style.transition = '';
            }, 200);
        }, 200);
    }

    enhanceHoverEffect(badge) {
        badge.style.transform = 'scale(1.15)';
        badge.style.boxShadow = '0 6px 16px rgba(0,0,0,0.4)';
    }

    removeHoverEffect(badge) {
        badge.style.transform = 'scale(1)';
        badge.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
    }

    showEnhancedTooltip(badge) {
        const title = badge.getAttribute('title');
        if (!title) return;

        // Remove existing tooltip
        this.hideEnhancedTooltip(badge);

        const tooltip = document.createElement('div');
        tooltip.className = 'enhanced-tooltip';
        tooltip.textContent = title;
        tooltip.style.cssText = `
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.95);
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 10000;
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        `;

        badge.appendChild(tooltip);
        
        // Add arrow
        const arrow = document.createElement('div');
        arrow.style.cssText = `
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: rgba(0, 0, 0, 0.95);
        `;
        tooltip.appendChild(arrow);

        // Animate in
        setTimeout(() => {
            tooltip.style.opacity = '1';
        }, 10);
    }

    hideEnhancedTooltip(badge) {
        const existingTooltip = badge.querySelector('.enhanced-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }
    }

    // Static method to update notification count
    static updateNotificationCount(selector, count) {
        const badge = document.querySelector(selector);
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Static method to add a new notification
    static addNotification(containerSelector, type, message, count = null) {
        const container = document.querySelector(containerSelector);
        if (!container) return;

        const badge = document.createElement('span');
        badge.className = `notification-badge notification-badge--${type}`;
        badge.setAttribute('title', message);
        
        if (count !== null) {
            badge.textContent = count > 99 ? '99+' : count;
        } else {
            badge.innerHTML = '<i class="ti ti-alert-circle"></i>';
        }

        container.appendChild(badge);
        
        // Initialize the new badge
        const notificationUI = new NotificationUI();
        notificationUI.setupEventListeners();
    }

    // Static method to remove a notification
    static removeNotification(selector) {
        const badge = document.querySelector(selector);
        if (badge) {
            badge.remove();
        }
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new NotificationUI();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = NotificationUI;
} 