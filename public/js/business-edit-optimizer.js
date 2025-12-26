/**
 * Business Edit Page Optimizer
 * Consolidates essential JavaScript functionality to improve loading performance
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeBusinessEdit();
    });
    
    function initializeBusinessEdit() {
        // Initialize tooltips
        initializeTooltips();
        
        // Initialize form validation
        initializeFormValidation();
        
        // Initialize image previews
        initializeImagePreviews();
        
        // Initialize dynamic content
        initializeDynamicContent();
        
        // Initialize theme switching
        initializeThemeSwitching();
        
        // Initialize contact icons
        initializeContactIcons();
        
        // Initialize social media icons
        initializeSocialIcons();
        
        // Initialize gallery functionality
        initializeGallery();
        
        // Initialize testimonials
        initializeTestimonials();
        
        // Initialize services
        initializeServices();
        
        // Initialize products
        initializeProducts();
        
        // Initialize appointments
        initializeAppointments();
        
        // Initialize QR code generation
        initializeQRCode();
        
        // Initialize copy functionality
        initializeCopyFunctions();
        
        // Initialize preview functionality
        initializePreview();
    }
    
    function initializeTooltips() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    function initializeFormValidation() {
        // Basic form validation
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }
    
    function initializeImagePreviews() {
        // Image preview functionality
        document.querySelectorAll('input[type="file"]').forEach(function(input) {
            input.addEventListener('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    var preview = document.querySelector('#' + e.target.id + '_preview') || 
                                 e.target.parentNode.querySelector('.image-preview');
                    
                    if (preview) {
                        reader.onload = function(e) {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });
        });
    }
    
    function initializeDynamicContent() {
        // Dynamic content loading
        var dynamicElements = document.querySelectorAll('[data-dynamic]');
        dynamicElements.forEach(function(element) {
            var url = element.getAttribute('data-dynamic');
            if (url) {
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        element.innerHTML = html;
                    })
                    .catch(error => console.log('Dynamic content loading failed:', error));
            }
        });
    }
    
    function initializeThemeSwitching() {
        // Theme switching functionality
        document.querySelectorAll('.theme-selector').forEach(function(selector) {
            selector.addEventListener('change', function(e) {
                var theme = e.target.value;
                var color = e.target.getAttribute('data-color') || 'color1';
                
                // Update theme path
                var themePath = `/custom/${theme}/icon/${color}/`;
                window.theme_path = themePath;
                
                // Update contact icons
                updateContactIcons(theme, color);
                
                // Update social icons
                updateSocialIcons(theme, color);
            });
        });
    }
    
    function initializeContactIcons() {
        // Contact icon functionality
        document.querySelectorAll('.contact-icon-selector').forEach(function(selector) {
            selector.addEventListener('change', function(e) {
                var iconType = e.target.value;
                var iconContainer = e.target.closest('.contact-item').querySelector('.icon-preview');
                
                if (iconContainer) {
                    var iconPath = window.theme_path + 'contact/' + iconType.toLowerCase() + '.svg';
                    loadSVGIcon(iconPath, iconContainer);
                }
            });
        });
    }
    
    function initializeSocialIcons() {
        // Social media icon functionality
        document.querySelectorAll('.social-icon-selector').forEach(function(selector) {
            selector.addEventListener('change', function(e) {
                var iconType = e.target.value;
                var iconContainer = e.target.closest('.social-item').querySelector('.icon-preview');
                
                if (iconContainer) {
                    var iconPath = window.theme_path + 'social/' + iconType.toLowerCase() + '.svg';
                    loadSVGIcon(iconPath, iconContainer);
                }
            });
        });
    }
    
    function loadSVGIcon(path, container) {
        fetch(path)
            .then(response => response.text())
            .then(svg => {
                container.innerHTML = svg;
            })
            .catch(error => {
                console.log('Icon loading failed:', error);
                // Fallback icon
                container.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"/></svg>';
            });
    }
    
    function updateContactIcons(theme, color) {
        var contactIcons = document.querySelectorAll('.contact-icon-preview');
        contactIcons.forEach(function(icon) {
            var iconType = icon.getAttribute('data-icon-type');
            if (iconType) {
                var iconPath = `/custom/${theme}/icon/${color}/contact/${iconType.toLowerCase()}.svg`;
                loadSVGIcon(iconPath, icon);
            }
        });
    }
    
    function updateSocialIcons(theme, color) {
        var socialIcons = document.querySelectorAll('.social-icon-preview');
        socialIcons.forEach(function(icon) {
            var iconType = icon.getAttribute('data-icon-type');
            if (iconType) {
                var iconPath = `/custom/${theme}/icon/${color}/social/${iconType.toLowerCase()}.svg`;
                loadSVGIcon(iconPath, icon);
            }
        });
    }
    
    function initializeGallery() {
        // Gallery functionality
        var galleryItems = document.querySelectorAll('.gallery-item');
        galleryItems.forEach(function(item) {
            var deleteBtn = item.querySelector('.delete-gallery-item');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this item?')) {
                        item.remove();
                    }
                });
            }
        });
    }
    
    function initializeTestimonials() {
        // Testimonials functionality
        var testimonialItems = document.querySelectorAll('.testimonial-item');
        testimonialItems.forEach(function(item) {
            var deleteBtn = item.querySelector('.delete-testimonial');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this testimonial?')) {
                        item.remove();
                    }
                });
            }
        });
    }
    
    function initializeServices() {
        // Services functionality
        var serviceItems = document.querySelectorAll('.service-item');
        serviceItems.forEach(function(item) {
            var deleteBtn = item.querySelector('.delete-service');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this service?')) {
                        item.remove();
                    }
                });
            }
        });
    }
    
    function initializeProducts() {
        // Products functionality
        var productItems = document.querySelectorAll('.product-item');
        productItems.forEach(function(item) {
            var deleteBtn = item.querySelector('.delete-product');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('Are you sure you want to delete this product?')) {
                        item.remove();
                    }
                });
            }
        });
    }
    
    function initializeAppointments() {
        // Appointments functionality
        var appointmentSlots = document.querySelectorAll('.appointment-slot');
        appointmentSlots.forEach(function(slot) {
            slot.addEventListener('click', function() {
                this.classList.toggle('selected');
            });
        });
    }
    
    function initializeQRCode() {
        // QR Code generation
        var qrCodeElements = document.querySelectorAll('.qr-code-generator');
        qrCodeElements.forEach(function(element) {
            var data = element.getAttribute('data-url');
            if (data && typeof QRCode !== 'undefined') {
                new QRCode(element, {
                    text: data,
                    width: 128,
                    height: 128
                });
            }
        });
    }
    
    function initializeCopyFunctions() {
        // Copy to clipboard functionality
        document.querySelectorAll('.copy-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var text = this.getAttribute('data-copy');
                if (text) {
                    navigator.clipboard.writeText(text).then(function() {
                        // Show success message
                        showToast('Copied to clipboard!', 'success');
                    }).catch(function() {
                        // Fallback for older browsers
                        var textArea = document.createElement('textarea');
                        textArea.value = text;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        showToast('Copied to clipboard!', 'success');
                    });
                }
            });
        });
    }
    
    function initializePreview() {
        // Preview functionality
        document.querySelectorAll('.preview-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.getAttribute('data-preview-url');
                if (url) {
                    window.open(url, '_blank');
                }
            });
        });
    }
    
    function showToast(message, type) {
        // Simple toast notification
        var toast = document.createElement('div');
        toast.className = 'toast-notification ' + (type || 'info');
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 20px;
            background: ${type === 'success' ? '#28a745' : '#007bff'};
            color: white;
            border-radius: 4px;
            z-index: 9999;
            animation: slideIn 0.3s ease;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(function() {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(function() {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    // Add CSS animations
    var style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
    
})(); 