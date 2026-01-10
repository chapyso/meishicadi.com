@props(['business', 'existingPasses' => [], 'showFallback' => false, 'googleWalletService' => null])

<div class="wallet-buttons-container">
    <!-- Alert Container for Messages -->
    <div id="alert-container"></div>
    
    <!-- Main Wallet Buttons -->
    <div class="row g-4 mb-4">
        <!-- Apple Wallet Button -->
        <div class="col-md-6">
            <div class="wallet-button-card apple-wallet-card" 
                 data-bs-toggle="tooltip" 
                 data-bs-placement="top" 
                 title="Add your business card to Apple Wallet for easy access on iPhone and Apple Watch. Works offline and updates automatically.">
                <div class="wallet-button-content">
                    @if(isset($existingPasses['apple']))
                        <div class="wallet-status-badge success">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ __('Generated') }}</span>
                        </div>
                        <div class="wallet-actions">
                            <a href="{{ route('wallet.apple.download', $existingPasses['apple']->pass_id) }}" 
                               class="modern-wallet-btn apple-wallet-btn">
                                <div class="btn-icon">
                                    <i class="fab fa-apple"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">Add to Apple Wallet</span>
                                    <span class="btn-subtitle">iPhone & Apple Watch</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                            <div class="wallet-secondary-actions">
                                <button type="button" class="btn btn-outline-dark btn-sm" 
                                        onclick="copyWalletLink('apple', '{{ route('wallet.apple.download', $existingPasses['apple']->pass_id) }}')">
                                    <i class="fas fa-copy"></i> {{ __('Copy Link') }}
                                </button>
                                <button type="button" class="btn btn-outline-dark btn-sm" 
                                        onclick="showQRCode('apple', '{{ route('wallet.apple.download', $existingPasses['apple']->pass_id) }}')">
                                    <i class="fas fa-qrcode"></i> {{ __('Show QR') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="wallet-button-wrapper">
                            <button type="button" 
                                    class="modern-wallet-btn apple-wallet-btn" 
                                    id="apple-wallet-btn" 
                                    onclick="generateApplePass()"
                                    data-business-id="{{ $business->id }}">
                                <div class="btn-icon">
                                    <i class="fab fa-apple"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">Add to Apple Wallet</span>
                                    <span class="btn-subtitle">iPhone & Apple Watch</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                                <div class="btn-loading-spinner" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Google Wallet Button -->
        <div class="col-md-6">
            <div class="wallet-button-card google-wallet-card" 
                 data-bs-toggle="tooltip" 
                 data-bs-placement="top" 
                 title="Add your business card to Google Wallet for easy access on Android devices. Works offline and updates automatically.">
                <div class="wallet-button-content">
                    @if(isset($existingPasses['google']))
                        <div class="wallet-status-badge success">
                            <i class="fas fa-check-circle"></i>
                            <span>{{ __('Generated') }}</span>
                        </div>
                        <div class="wallet-actions">
                            <a href="{{ $googleWalletService ? $googleWalletService->getPassSaveUrl($existingPasses['google']) : '#' }}" 
                               class="modern-wallet-btn google-wallet-btn" 
                               target="_blank">
                                <div class="btn-icon">
                                    <i class="fab fa-google"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">Add to Google Wallet</span>
                                    <span class="btn-subtitle">Android Devices</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                            <div class="wallet-secondary-actions">
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="copyWalletLink('google', '{{ $googleWalletService ? $googleWalletService->getPassSaveUrl($existingPasses['google']) : '#' }}')">
                                    <i class="fas fa-copy"></i> {{ __('Copy Link') }}
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        onclick="showQRCode('google', '{{ $googleWalletService ? $googleWalletService->getPassSaveUrl($existingPasses['google']) : '#' }}')">
                                    <i class="fas fa-qrcode"></i> {{ __('Show QR') }}
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="wallet-button-wrapper">
                            <button type="button" 
                                    class="modern-wallet-btn google-wallet-btn" 
                                    id="google-wallet-btn" 
                                    onclick="generateGooglePass()"
                                    data-business-id="{{ $business->id }}">
                                <div class="btn-icon">
                                    <i class="fab fa-google"></i>
                                </div>
                                <div class="btn-content">
                                    <span class="btn-title">Add to Google Wallet</span>
                                    <span class="btn-subtitle">Android Devices</span>
                                </div>
                                <div class="btn-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                                <div class="btn-loading-spinner" style="display: none;">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fallback Options (shown when showFallback is true) -->
    @if($showFallback)
        <div class="fallback-options mt-4">
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-info-circle"></i> {{ __('Alternative Options') }}
                </h6>
                <p class="mb-2">{{ __('If you didn\'t receive the email or need alternative access:') }}</p>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="resendWalletEmail()">
                        <i class="fas fa-envelope"></i> {{ __('Resend Email') }}
                    </button>
                    <button type="button" class="btn btn-outline-info btn-sm" onclick="showAllWalletLinks()">
                        <i class="fas fa-link"></i> {{ __('Show All Links') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.wallet-buttons-container {
    margin: 2rem 0;
}

.wallet-button-card {
    border: 1px solid #e9ecef;
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    position: relative;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.wallet-button-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #007bff, #0056b3);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.wallet-button-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    border-color: #007bff;
}

.wallet-button-card:hover::before {
    opacity: 1;
}

.apple-wallet-card:hover {
    border-color: #000;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.apple-wallet-card::before {
    background: linear-gradient(90deg, #000, #333);
}

.google-wallet-card:hover {
    border-color: #4285F4;
    box-shadow: 0 8px 25px rgba(66, 133, 244, 0.15);
}

.google-wallet-card::before {
    background: linear-gradient(90deg, #4285F4, #1a73e8);
}

.wallet-button-content {
    text-align: center;
}

.wallet-status-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    z-index: 10;
}

.wallet-status-badge.success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
    border: 1px solid #b8dacc;
}

/* Modern Wallet Button Design */
.modern-wallet-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 16px 20px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #495057;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    min-height: 72px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.modern-wallet-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.5s ease;
}

.modern-wallet-btn:hover::before {
    left: 100%;
}

.modern-wallet-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    text-decoration: none;
    color: inherit;
}

.modern-wallet-btn:active {
    transform: translateY(0);
}

/* Apple Wallet Button */
.apple-wallet-btn {
    background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
    color: white;
    border: 1px solid #333;
}

.apple-wallet-btn:hover {
    background: linear-gradient(135deg, #1a1a1a 0%, #000 100%);
    color: white;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

/* Google Wallet Button */
.google-wallet-btn {
    background: linear-gradient(135deg, #4285F4 0%, #1a73e8 100%);
    color: white;
    border: 1px solid #1a73e8;
}

.google-wallet-btn:hover {
    background: linear-gradient(135deg, #1a73e8 0%, #4285F4 100%);
    color: white;
    box-shadow: 0 6px 20px rgba(66, 133, 244, 0.3);
}

/* Button Components */
.btn-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    border-radius: 12px;
    font-size: 24px;
    flex-shrink: 0;
}

.apple-wallet-btn .btn-icon {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.google-wallet-btn .btn-icon {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.btn-content {
    flex: 1;
    text-align: left;
    margin: 0 16px;
}

.btn-title {
    display: block;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: 4px;
}

.btn-subtitle {
    display: block;
    font-size: 12px;
    opacity: 0.8;
    font-weight: 400;
}

.btn-arrow {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.apple-wallet-btn .btn-arrow {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.google-wallet-btn .btn-arrow {
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

.modern-wallet-btn:hover .btn-arrow {
    transform: translateX(4px);
    background: rgba(255, 255, 255, 0.2);
}

.btn-loading-spinner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 20px;
}

.wallet-actions {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.wallet-secondary-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 1rem;
}

.wallet-secondary-actions .btn {
    border-radius: 8px;
    font-size: 12px;
    padding: 6px 12px;
    font-weight: 500;
}

.fallback-options {
    border-top: 1px solid #e9ecef;
    padding-top: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .wallet-button-card {
        padding: 1rem;
    }
    
    .modern-wallet-btn {
        padding: 12px 16px;
        min-height: 64px;
    }
    
    .btn-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
    
    .btn-title {
        font-size: 14px;
    }
    
    .btn-subtitle {
        font-size: 11px;
    }
    
    .btn-arrow {
        width: 28px;
        height: 28px;
        font-size: 12px;
    }
    
    .wallet-secondary-actions {
        flex-direction: column;
    }
    
    .wallet-secondary-actions .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .btn-content {
        margin: 0 12px;
    }
    
    .modern-wallet-btn {
        padding: 10px 14px;
        min-height: 56px;
    }
    
    .btn-icon {
        width: 36px;
        height: 36px;
        font-size: 18px;
    }
    
    .btn-title {
        font-size: 13px;
    }
    
    .btn-subtitle {
        font-size: 10px;
    }
}
</style>

<script>
function generateApplePass() {
    const businessId = document.getElementById('apple-wallet-btn').dataset.businessId;
    generateWalletPass('apple', businessId);
}

function generateGooglePass() {
    const businessId = document.getElementById('google-wallet-btn').dataset.businessId;
    generateWalletPass('google', businessId);
}

function generateWalletPass(walletType, businessId) {
    const btn = document.getElementById(`${walletType}-wallet-btn`);
    const spinner = btn.querySelector('.btn-loading-spinner');
    const badge = btn.querySelector('.wallet-badge');
    
    // Show loading state
    btn.disabled = true;
    spinner.style.display = 'block';
    badge.style.opacity = '0.3';
    
    const url = walletType === 'apple' 
        ? `/wallet/apple/${businessId}`
        : `/wallet/google/${businessId}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', data.message);
            
            // Send email notification
            if (data.download_url || data.save_url) {
                showSuccessModal(data.message, data.download_url || data.save_url, walletType);
            }
            
            // Reload page after 3 seconds to show new buttons
            setTimeout(() => {
                location.reload();
            }, 3000);
        } else {
            showAlert('error', data.error || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'An error occurred while generating the wallet pass');
    })
    .finally(() => {
        // Reset button state
        btn.disabled = false;
        spinner.style.display = 'none';
        badge.style.opacity = '1';
    });
}

function copyWalletLink(walletType, url) {
    navigator.clipboard.writeText(url).then(() => {
        showAlert('success', `${walletType === 'apple' ? 'Apple' : 'Google'} Wallet link copied to clipboard!`);
    }).catch(() => {
        // Fallback for older browsers
        const tempInput = document.createElement('textarea');
        tempInput.value = url;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        showAlert('success', `${walletType === 'apple' ? 'Apple' : 'Google'} Wallet link copied to clipboard!`);
    });
}

function showQRCode(walletType, url) {
    // Create QR code modal
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'qrModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-qrcode"></i> 
                        ${walletType === 'apple' ? 'Apple' : 'Google'} Wallet QR Code
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="qrcode"></div>
                    <p class="mt-3 text-muted">
                        Scan this QR code with your phone to add the wallet pass
                    </p>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Generate QR code (you'll need to include a QR code library)
    if (typeof QRCode !== 'undefined') {
        new QRCode(document.getElementById('qrcode'), {
            text: url,
            width: 200,
            height: 200
        });
    } else {
        document.getElementById('qrcode').innerHTML = `
            <div class="alert alert-warning">
                QR code generation requires QRCode.js library. 
                <a href="${url}" target="_blank">Click here to open the wallet pass</a>
            </div>
        `;
    }
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
}

function showAlert(type, message) {
    let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    let icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    
    let alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="${icon}"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const alertContainer = document.getElementById('alert-container');
    if (alertContainer) {
        alertContainer.innerHTML = alertHtml;
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.remove();
            }
        }, 5000);
    }
}

function showSuccessModal(message, url, walletType) {
    // Create success modal
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'successModal';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle"></i> Wallet Pass Generated Successfully!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-wallet fa-3x text-success"></i>
                    </div>
                    <p>${message}</p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>What's Next?</strong><br>
                        Your wallet pass has been generated and an email has been sent to your registered email address with download instructions.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="downloadBtn">
                        <i class="fas fa-download"></i> Download Now
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    // Handle download button
    document.getElementById('downloadBtn').addEventListener('click', function() {
        if (walletType === 'apple') {
            window.location.href = url;
        } else {
            window.open(url, '_blank');
        }
    });
    
    modal.addEventListener('hidden.bs.modal', () => {
        document.body.removeChild(modal);
    });
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Check if wallet badges loaded properly
    checkWalletBadges();
});

function checkWalletBadges() {
    const badges = document.querySelectorAll('.wallet-badge');
    badges.forEach(badge => {
        badge.addEventListener('error', function() {
            // Hide the broken image and show fallback text
            this.style.display = 'none';
            const fallback = this.nextElementSibling;
            if (fallback && fallback.classList.contains('wallet-text-fallback')) {
                fallback.style.display = 'block';
            }
        });
        
        // Also check if image loaded successfully
        badge.addEventListener('load', function() {
            // Ensure the image is visible and fallback is hidden
            this.style.display = 'block';
            const fallback = this.nextElementSibling;
            if (fallback && fallback.classList.contains('wallet-text-fallback')) {
                fallback.style.display = 'none';
            }
        });
    });
}
</script> 