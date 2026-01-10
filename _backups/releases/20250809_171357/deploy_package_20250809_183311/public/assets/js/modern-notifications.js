/**
 * Modern Notification System
 * Replaces browser confirm() dialogs with beautiful custom modals
 */

class ModernNotification {
    constructor() {
        this.overlay = null;
        this.modal = null;
        this.resolve = null;
        this.reject = null;
        this.init();
    }

    init() {
        // Create overlay and modal elements
        this.createElements();
        this.bindEvents();
    }

    createElements() {
        // Create overlay
        this.overlay = document.createElement('div');
        this.overlay.className = 'modern-notification-overlay';
        
        // Create modal
        this.modal = document.createElement('div');
        this.modal.className = 'modern-notification-modal';
        
        // Create header
        const header = document.createElement('div');
        header.className = 'modern-notification-header';
        
        const icon = document.createElement('div');
        icon.className = 'modern-notification-icon';
        
        const title = document.createElement('h3');
        title.className = 'modern-notification-title';
        
        const message = document.createElement('p');
        message.className = 'modern-notification-message';
        
        header.appendChild(icon);
        header.appendChild(title);
        header.appendChild(message);
        
        // Create body
        const body = document.createElement('div');
        body.className = 'modern-notification-body';
        
        const actions = document.createElement('div');
        actions.className = 'modern-notification-actions';
        
        body.appendChild(actions);
        
        // Assemble modal
        this.modal.appendChild(header);
        this.modal.appendChild(body);
        
        // Assemble overlay
        this.overlay.appendChild(this.modal);
        
        // Store references
        this.icon = icon;
        this.title = title;
        this.message = message;
        this.actions = actions;
    }

    bindEvents() {
        // Close on overlay click
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.hide();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.overlay.classList.contains('show')) {
                this.hide();
            }
        });
    }

    show(options = {}) {
        return new Promise((resolve, reject) => {
            this.resolve = resolve;
            this.reject = reject;

            // Set content
            this.title.textContent = options.title || '{{ __("Confirm Action") }}';
            this.message.textContent = options.message || '{{ __("Are you sure you want to proceed?") }}';
            
            // Set icon
            this.icon.className = 'modern-notification-icon ' + (options.type || 'warning');
            this.icon.innerHTML = this.getIcon(options.type || 'warning');

            // Clear previous buttons
            this.actions.innerHTML = '';

            // Create buttons
            if (options.showCancel !== false) {
                const cancelBtn = this.createButton({
                    text: options.cancelText || '{{ __("Cancel") }}',
                    type: 'cancel',
                    onClick: () => this.hide(false)
                });
                this.actions.appendChild(cancelBtn);
            }

            const confirmBtn = this.createButton({
                text: options.confirmText || '{{ __("Confirm") }}',
                type: options.confirmType || 'confirm',
                onClick: () => this.hide(true)
            });
            this.actions.appendChild(confirmBtn);

            // Add to DOM
            document.body.appendChild(this.overlay);

            // Show with animation
            requestAnimationFrame(() => {
                this.overlay.classList.add('show');
            });
        });
    }

    createButton(options) {
        const button = document.createElement('button');
        button.className = `modern-notification-btn ${options.type}`;
        button.innerHTML = `
            <span class="btn-text">${options.text}</span>
            <span class="btn-spinner">
                <i class="ti ti-loader"></i>
            </span>
        `;
        
        button.addEventListener('click', options.onClick);
        
        return button;
    }

    getIcon(type) {
        const icons = {
            success: '<i class="ti ti-check"></i>',
            warning: '<i class="ti ti-alert-triangle"></i>',
            danger: '<i class="ti ti-alert-circle"></i>',
            info: '<i class="ti ti-info-circle"></i>'
        };
        return icons[type] || icons.warning;
    }

    hide(result) {
        // Hide with animation
        this.overlay.classList.remove('show');
        
        setTimeout(() => {
            // Remove from DOM
            if (this.overlay.parentNode) {
                this.overlay.parentNode.removeChild(this.overlay);
            }
            
            // Resolve promise
            if (this.resolve) {
                this.resolve(result);
            }
        }, 300);
    }

    // Convenience methods
    confirm(message, title = '{{ __("Confirm Action") }}') {
        return this.show({
            title: title,
            message: message,
            type: 'warning'
        });
    }

    success(message, title = '{{ __("Success") }}') {
        return this.show({
            title: title,
            message: message,
            type: 'success',
            showCancel: false,
            confirmText: '{{ __("OK") }}'
        });
    }

    error(message, title = '{{ __("Error") }}') {
        return this.show({
            title: title,
            message: message,
            type: 'danger',
            showCancel: false,
            confirmText: '{{ __("OK") }}'
        });
    }

    info(message, title = '{{ __("Information") }}') {
        return this.show({
            title: title,
            message: message,
            type: 'info',
            showCancel: false,
            confirmText: '{{ __("OK") }}'
        });
    }
}

// Create global instance
window.ModernNotification = new ModernNotification();

// Replace browser confirm with modern notification
window.originalConfirm = window.confirm;
window.confirm = function(message) {
    return ModernNotification.confirm(message);
};

// Add to window for global access
window.showModernConfirm = function(message, title, options = {}) {
    return ModernNotification.show({
        title: title || '{{ __("Confirm Action") }}',
        message: message,
        ...options
    });
};

// Business-specific confirmation function
window.confirmBusinessAction = function(message, isActivation = true) {
    return ModernNotification.show({
        title: isActivation ? 'Activate Business' : 'Deactivate Business',
        message: message,
        type: isActivation ? 'success' : 'warning',
        confirmText: isActivation ? 'Activate' : 'Deactivate',
        confirmType: isActivation ? 'success' : 'danger'
    });
};

// Test function to verify the system is working
window.testModernNotification = function() {
    return ModernNotification.show({
        title: 'Test Notification',
        message: 'This is a test of the modern notification system.',
        type: 'info',
        confirmText: 'OK',
        confirmType: 'info'
    });
}; 