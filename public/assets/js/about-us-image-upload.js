/**
 * Enhanced About Us Image Upload Functionality
 * Features: Drag & Drop, Image Preview, File Validation, Better UX
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeImageUploads();
    initializeCharacterCounters();
});

function initializeImageUploads() {
    const imageContainers = document.querySelectorAll('.image-picker');
    
    imageContainers.forEach(container => {
        const previewArea = container.querySelector('.previewImage');
        const fileInput = container.querySelector('.image-upload');
        const uploadOverlay = container.querySelector('.upload-overlay');
        const fileInfo = container.querySelector('.file-info');
        const removeBtn = container.querySelector('.remove-image-btn');
        
        if (previewArea && fileInput) {
            setupDragAndDrop(previewArea, fileInput, uploadOverlay);
            setupFileInput(fileInput, previewArea, fileInfo);
            setupRemoveButton(removeBtn, previewArea, fileInput);
        }
    });
}

function initializeCharacterCounters() {
    const textareas = document.querySelectorAll('.description-textarea');
    
    textareas.forEach(textarea => {
        const counter = document.querySelector(`[data-target="${textarea.name}"]`);
        if (counter) {
            updateCharacterCount(textarea, counter);
            
            textarea.addEventListener('input', function() {
                updateCharacterCount(this, counter);
            });
        }
    });
}

function updateCharacterCount(textarea, counter) {
    const currentCount = textarea.value.length;
    const maxCount = textarea.maxLength || 1000;
    const currentCountSpan = counter.querySelector('.current-count');
    
    if (currentCountSpan) {
        currentCountSpan.textContent = currentCount;
    }
    
    // Remove existing classes
    counter.classList.remove('warning', 'danger');
    
    // Update counter color and classes based on usage
    if (currentCount > maxCount * 0.9) {
        counter.style.color = '#dc3545'; // Red when near limit
        counter.classList.add('danger');
    } else if (currentCount > maxCount * 0.7) {
        counter.style.color = '#ffc107'; // Yellow when getting close
        counter.classList.add('warning');
    } else {
        counter.style.color = '#6c757d'; // Default gray
    }
}

function setupDragAndDrop(previewArea, fileInput, uploadOverlay) {
    if (!previewArea || !uploadOverlay) return;
    
    // Drag and Drop Events
    previewArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadOverlay.classList.remove('d-none');
        previewArea.style.borderColor = '#007bff';
    });
    
    previewArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!previewArea.contains(e.relatedTarget)) {
            uploadOverlay.classList.add('d-none');
            previewArea.style.borderColor = '#dee2e6';
        }
    });
    
    previewArea.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadOverlay.classList.add('d-none');
        previewArea.style.borderColor = '#dee2e6';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            handleFile(files[0], fileInput, previewArea);
        }
    });
    
    // Click to upload
    previewArea.addEventListener('click', function() {
        fileInput.click();
    });
}

function setupFileInput(fileInput, previewArea, fileInfo) {
    if (!fileInput) return;
    
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFile(file, fileInput, previewArea, fileInfo);
        }
    });
}

function setupRemoveButton(removeBtn, previewArea, fileInput) {
    if (!removeBtn) return;
    
    removeBtn.addEventListener('click', function() {
        const aboutId = this.getAttribute('data-about-id');
        
        if (confirm('Are you sure you want to remove this image?')) {
            // Clear the file input
            fileInput.value = '';
            
            // Reset preview to default with placeholder
            previewArea.style.backgroundImage = 'none';
            previewArea.style.backgroundColor = '#f8f9fa';
            previewArea.style.borderColor = '#dee2e6';
            previewArea.style.backgroundSize = 'contain';
            previewArea.style.backgroundRepeat = 'no-repeat';
            previewArea.style.backgroundPosition = 'center';
            
            // Hide remove button
            this.style.display = 'none';
            
            // Hide success badge if it exists
            const badge = previewArea.querySelector('.badge');
            if (badge) {
                badge.style.display = 'none';
            }
            
            // Show placeholder
            showPlaceholder(previewArea);
            
            // Clear file info
            const fileInfo = previewArea.parentElement.querySelector('.file-info');
            if (fileInfo) {
                fileInfo.classList.add('d-none');
            }
            
            // Show success message
            showNotification('Image removed successfully', 'success');
        }
    });
}

function showPlaceholder(previewArea) {
    // Remove existing placeholder if any
    const existingPlaceholder = previewArea.querySelector('.placeholder-content');
    if (existingPlaceholder) {
        existingPlaceholder.remove();
    }
    
    // Create and show new placeholder
    const placeholder = document.createElement('div');
    placeholder.className = 'placeholder-content position-absolute top-50 start-50 translate-middle text-center text-muted';
    placeholder.innerHTML = `
        <i class="fas fa-image fa-3x mb-2 opacity-50"></i>
        <div class="small">No Image Uploaded</div>
    `;
    previewArea.appendChild(placeholder);
}

function handleFile(file, fileInput, previewArea, fileInfo) {
    // Validate file type
    if (!isValidImageFile(file)) {
        showNotification('Please select a valid image file (JPG, PNG, GIF, BMP, WebP, SVG)', 'error');
        return;
    }
    
    // Validate file size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        showNotification('File size must be less than 10MB', 'error');
        return;
    }
    
    // Create preview
    const reader = new FileReader();
    reader.onload = function(e) {
        // Set image with contain sizing to show full image
        previewArea.style.backgroundImage = `url('${e.target.result}')`;
        previewArea.style.backgroundColor = 'transparent';
        previewArea.style.borderColor = '#28a745';
        previewArea.style.backgroundSize = 'contain';
        previewArea.style.backgroundRepeat = 'no-repeat';
        previewArea.style.backgroundPosition = 'center';
        
        // Remove placeholder if it exists
        const placeholder = previewArea.querySelector('.placeholder-content');
        if (placeholder) {
            placeholder.remove();
        }
        
        // Show file info
        if (fileInfo) {
            showFileInfo(fileInfo, file);
        }
        
        // Show success message
        showNotification('Image selected successfully', 'success');
        
        // Show remove button if it exists
        const removeBtn = previewArea.parentElement.querySelector('.remove-image-btn');
        if (removeBtn) {
            removeBtn.style.display = 'inline-block';
        }
        
        // Show or create success badge
        showSuccessBadge(previewArea);
    };
    reader.readAsDataURL(file);
}

function showSuccessBadge(previewArea) {
    // Remove existing badge if any
    const existingBadge = previewArea.querySelector('.badge');
    if (existingBadge) {
        existingBadge.style.display = 'block';
        return;
    }
    
    // Create new success badge
    const badge = document.createElement('div');
    badge.className = 'position-absolute top-0 end-0 m-2';
    badge.innerHTML = '<span class="badge bg-success">Image Uploaded</span>';
    previewArea.appendChild(badge);
}

function isValidImageFile(file) {
    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp', 'image/webp', 'image/svg+xml'];
    return validTypes.includes(file.type);
}

function showFileInfo(fileInfoElement, file) {
    if (!fileInfoElement) return;
    
    const fileName = fileInfoElement.querySelector('.file-name');
    const fileSize = fileInfoElement.querySelector('.file-size');
    
    if (fileName) {
        fileName.textContent = file.name;
    }
    
    if (fileSize) {
        fileSize.textContent = formatFileSize(file.size);
    }
    
    fileInfoElement.classList.remove('d-none');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getDefaultImageUrl() {
    return '/front/images/about.png';
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

// Add some CSS for better visual feedback
const style = document.createElement('style');
style.textContent = `
    .image-picker .previewImage {
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        min-height: 300px !important;
        width: 100%;
        background-color: #f8f9fa;
    }
    
    .image-picker .previewImage:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }
    
    .image-picker .previewImage:not([style*="background-image"]) {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .image-picker .previewImage[style*="background-image"] {
        border-color: #28a745;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.2);
        background-color: white !important;
    }
    
    .image-picker .previewImage[style*="background-image"]:hover {
        box-shadow: 0 4px 20px rgba(40, 167, 69, 0.3);
    }
    
    .upload-overlay {
        transition: all 0.3s ease;
        backdrop-filter: blur(2px);
        background: rgba(0, 123, 255, 0.8) !important;
    }
    
    .upload-overlay:hover {
        background: rgba(0, 123, 255, 0.9) !important;
    }
    
    .file-info {
        background: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .remove-image-btn {
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .remove-image-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .upload-btn {
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .upload-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    
    .placeholder-content {
        pointer-events: none;
        z-index: 1;
    }
    
    .placeholder-content i {
        color: #adb5bd;
    }
    
    .badge {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-weight: 500;
    }
    
    .alert {
        animation: slideInRight 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    .about-section-card {
        transition: all 0.3s ease;
    }
    
    .about-section-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border-color: #007bff;
    }
    
    .character-counter {
        font-weight: 500;
        padding: 4px 8px;
        border-radius: 4px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }
    
    .character-counter.warning {
        background: #fff3cd;
        border-color: #ffc107;
        color: #856404 !important;
    }
    
    .character-counter.danger {
        background: #f8d7da;
        border-color: #dc3545;
        color: #721c24 !important;
    }
    
    .description-textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .description-textarea:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
`;
document.head.appendChild(style);
