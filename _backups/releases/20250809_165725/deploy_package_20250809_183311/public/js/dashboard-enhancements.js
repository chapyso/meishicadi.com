/**
 * Dashboard Enhancements - Modern UI Interactions
 * Enhances the dashboard with smooth animations, interactive elements, and better UX
 */

(function() {
    'use strict';

    // Initialize dashboard enhancements when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeDashboardEnhancements();
    });

    function initializeDashboardEnhancements() {
        // Initialize theme system
        initializeThemeSystem();
        
        // Add loading states to cards
        addLoadingStates();
        
        // Enhance card interactions
        enhanceCardInteractions();
        
        // Add smooth scrolling
        addSmoothScrolling();
        
        // Enhance form controls
        enhanceFormControls();
        
        // Add notification system
        initializeNotifications();
        
        // Add keyboard shortcuts
        addKeyboardShortcuts();
        
        // Add performance monitoring
        addPerformanceMonitoring();
        
        // Add theme toggle button
        addThemeToggleButton();
    }

    // Initialize theme system
    function initializeThemeSystem() {
        // Check for saved theme preference or default to light
        const savedTheme = localStorage.getItem('dashboard-theme') || 'light';
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        
        // Apply theme based on saved preference or system preference
        const themeToApply = savedTheme === 'auto' ? (systemPrefersDark ? 'dark' : 'light') : savedTheme;
        applyTheme(themeToApply);
        
        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (localStorage.getItem('dashboard-theme') === 'auto') {
                applyTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    // Apply theme to the document
    function applyTheme(theme) {
        const body = document.body;
        const html = document.documentElement;
        
        // Remove existing theme classes
        body.classList.remove('dark-theme', 'light-theme');
        html.removeAttribute('data-theme');
        
        // Apply new theme
        if (theme === 'dark') {
            body.classList.add('dark-theme');
            html.setAttribute('data-theme', 'dark');
            localStorage.setItem('dashboard-theme', 'dark');
        } else {
            body.classList.add('light-theme');
            html.setAttribute('data-theme', 'light');
            localStorage.setItem('dashboard-theme', 'light');
        }
        
        // Update theme toggle button if it exists
        updateThemeToggleButton(theme);
        
        // Trigger custom event for other components
        document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme } }));
    }

    // Add theme toggle button
    function addThemeToggleButton() {
        // Check if button already exists
        if (document.querySelector('.theme-toggle')) return;
        
        const toggleButton = document.createElement('button');
        toggleButton.className = 'theme-toggle';
        toggleButton.setAttribute('aria-label', 'Toggle theme');
        toggleButton.innerHTML = '<i class="ti ti-moon"></i>';
        
        // Add click event
        toggleButton.addEventListener('click', function() {
            const currentTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            applyTheme(newTheme);
            
            // Show notification
            showNotification(`Switched to ${newTheme} theme`, 'success', 2000);
        });
        
        // Add to body
        document.body.appendChild(toggleButton);
        
        // Update button icon
        updateThemeToggleButton(document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    }

    // Update theme toggle button icon
    function updateThemeToggleButton(theme) {
        const toggleButton = document.querySelector('.theme-toggle');
        if (toggleButton) {
            const icon = toggleButton.querySelector('i');
            if (theme === 'dark') {
                icon.className = 'ti ti-sun';
                toggleButton.setAttribute('aria-label', 'Switch to light theme');
            } else {
                icon.className = 'ti ti-moon';
                toggleButton.setAttribute('aria-label', 'Switch to dark theme');
            }
        }
    }

    // Add loading states to dashboard cards
    function addLoadingStates() {
        const cards = document.querySelectorAll('.dashboard-card');
        
        cards.forEach(card => {
            // Add loading class initially
            card.classList.add('loading');
            
            // Remove loading after content is loaded
            setTimeout(() => {
                card.classList.remove('loading');
            }, Math.random() * 1000 + 500); // Random delay between 500-1500ms
        });
    }

    // Enhance card interactions with hover effects and animations
    function enhanceCardInteractions() {
        const cards = document.querySelectorAll('.dashboard-card');
        
        cards.forEach(card => {
            // Add click ripple effect
            card.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' || e.target.closest('a')) return;
                
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.left = (e.clientX - card.offsetLeft) + 'px';
                ripple.style.top = (e.clientY - card.offsetTop) + 'px';
                ripple.style.width = ripple.style.height = '20px';
                
                card.style.position = 'relative';
                card.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });

            // Add hover sound effect (optional)
            card.addEventListener('mouseenter', function() {
                playHoverSound();
            });
        });
    }

    // Add smooth scrolling to the dashboard
    function addSmoothScrolling() {
        const scrollElements = document.querySelectorAll('a[href^="#"]');
        
        scrollElements.forEach(element => {
            element.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    }

    // Enhance form controls with better UX
    function enhanceFormControls() {
        const selectors = document.querySelectorAll('.time-selector, select, input');
        
        selectors.forEach(selector => {
            // Add focus effects
            selector.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            selector.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
            
            // Add change animations
            selector.addEventListener('change', function() {
                addChangeAnimation(this);
            });
        });
    }

    // Initialize notification system
    function initializeNotifications() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            const container = document.createElement('div');
            container.id = 'notification-container';
            container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            `;
            document.body.appendChild(container);
        }
    }

    // Show notification
    function showNotification(message, type = 'info', duration = 5000) {
        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        
        const bgColor = type === 'success' ? '#28a745' : 
                       type === 'error' ? '#dc3545' : 
                       type === 'warning' ? '#ffc107' : '#17a2b8';
        
        notification.style.cssText = `
            background: ${bgColor};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            font-weight: 500;
            max-width: 100%;
        `;
        
        notification.textContent = message;
        container.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, duration);
    }

    // Add keyboard shortcuts
    function addKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + K for quick search
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                showQuickSearch();
            }
            
            // Ctrl/Cmd + N for new card
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                triggerNewCard();
            }
            
            // Ctrl/Cmd + T for theme toggle
            if ((e.ctrlKey || e.metaKey) && e.key === 't') {
                e.preventDefault();
                toggleTheme();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                closeAllModals();
            }
        });
    }

    // Toggle theme
    function toggleTheme() {
        const currentTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        applyTheme(newTheme);
    }

    // Show quick search modal
    function showQuickSearch() {
        const searchModal = document.createElement('div');
        searchModal.innerHTML = `
            <div style="
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            ">
                <div style="
                    background: white;
                    padding: 2rem;
                    border-radius: 16px;
                    width: 90%;
                    max-width: 500px;
                    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                ">
                    <h3 style="margin-bottom: 1rem;">Quick Search</h3>
                    <input type="text" placeholder="Search..." style="
                        width: 100%;
                        padding: 1rem;
                        border: 2px solid #e9ecef;
                        border-radius: 12px;
                        font-size: 1rem;
                        outline: none;
                    " autofocus>
                    <div style="margin-top: 1rem; text-align: right;">
                        <button onclick="this.closest('div[style*=\"position: fixed\"]').remove()" style="
                            background: #6c757d;
                            color: white;
                            border: none;
                            padding: 0.5rem 1rem;
                            border-radius: 8px;
                            cursor: pointer;
                        ">Close</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(searchModal);
    }

    // Trigger new card creation
    function triggerNewCard() {
        const newCardBtn = document.querySelector('[data-url*="business.create"]');
        if (newCardBtn) {
            newCardBtn.click();
        } else {
            showNotification('New card creation not available', 'warning');
        }
    }

    // Close all modals
    function closeAllModals() {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (modal.classList.contains('show')) {
                const closeBtn = modal.querySelector('.btn-close, [data-bs-dismiss="modal"]');
                if (closeBtn) closeBtn.click();
            }
        });
    }

    // Add performance monitoring
    function addPerformanceMonitoring() {
        // Monitor page load performance
        window.addEventListener('load', function() {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            console.log(`Dashboard loaded in ${loadTime}ms`);
            
            if (loadTime > 3000) {
                showNotification('Dashboard loaded slowly. Consider optimizing.', 'warning');
            }
        });

        // Monitor card animations performance
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animationPlayState = 'running';
                }
            });
        });

        document.querySelectorAll('.dashboard-card').forEach(card => {
            observer.observe(card);
        });
    }

    // Add change animation to elements
    function addChangeAnimation(element) {
        element.style.transform = 'scale(1.05)';
        element.style.transition = 'transform 0.2s ease';
        
        setTimeout(() => {
            element.style.transform = 'scale(1)';
        }, 200);
    }

    // Play hover sound (optional - can be disabled)
    function playHoverSound() {
        // This is optional and can be disabled for better performance
        // Uncomment the following lines if you want hover sounds
        /*
        const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.volume = 0.1;
        audio.play().catch(() => {}); // Ignore errors
        */
    }

    // Add ripple animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
        
        .focused {
            transform: scale(1.02);
            transition: transform 0.2s ease;
        }
        
        .dashboard-card {
            cursor: pointer;
        }
        
        .dashboard-card:hover {
            cursor: pointer;
        }
        
        /* Enhanced focus states */
        .dashboard-card:focus-within {
            outline: 2px solid #667eea;
            outline-offset: 2px;
        }
        
        /* Smooth transitions for all interactive elements */
        button, a, input, select, textarea {
            transition: all 0.2s ease;
        }
        
        /* Loading animation enhancement */
        .dashboard-card.loading::after {
            background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.2), transparent);
        }
        
        /* Theme toggle button animations */
        .theme-toggle {
            transition: all 0.3s ease;
        }
        
        .theme-toggle:hover {
            transform: scale(1.1) rotate(180deg);
        }
        
        /* Theme transition animations */
        .dashboard-container,
        .dashboard-card,
        .page-title,
        .dash-sidebar {
            transition: all 0.3s ease;
        }
    `;
    document.head.appendChild(style);

    // Export functions for global use
    window.DashboardEnhancements = {
        showNotification,
        triggerNewCard,
        showQuickSearch,
        closeAllModals,
        applyTheme,
        toggleTheme
    };

})();

// Avoid duplicate listeners when the script is included more than once
if (!window.__dashboardEnhancementsErrorHooks) {
    window.__dashboardEnhancementsErrorHooks = true;

    // Throttle notifications to avoid spamming the UI
    let lastErrorTs = 0;
    function notifyOncePer(seconds, message) {
        const now = Date.now();
        if (now - lastErrorTs > seconds * 1000) {
            lastErrorTs = now;
            window.DashboardEnhancements && window.DashboardEnhancements.showNotification(message, 'error');
        }
    }

    // Global JS runtime error handling (ignore resource load errors)
    window.addEventListener('error', function(e) {
        // Ignore passive resource errors (img/script/css load errors)
        const isResourceError = !e.error && (e.target && (e.target.src || e.target.href));
        if (isResourceError) {
            // Still log for diagnostics but do not toast
            console.warn('Resource load error:', e.target && (e.target.src || e.target.href));
            return;
        }

        // Real runtime error
        console.error('Dashboard Error:', e.error || e.message);
        notifyOncePer(10, 'An unexpected error occurred. Check the console for details.');
    }, true);

    // Unhandled promise rejections
    window.addEventListener('unhandledrejection', function(e) {
        const reason = e.reason || {};
        console.error('Unhandled Promise Rejection:', reason);
        // Friendlier message for network issues
        const msg = (typeof reason === 'string' && /network|failed to fetch|load failed/i.test(reason)) ||
                    (reason && reason.message && /network|failed to fetch|load failed/i.test(reason.message))
                    ? 'A network error occurred. Please check your connection.'
                    : 'An unexpected error occurred. Check the console for details.';
        notifyOncePer(10, msg);
    });
}