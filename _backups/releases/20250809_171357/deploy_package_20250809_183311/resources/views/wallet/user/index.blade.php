@extends('layouts.admin')

@section('page-title')
    {{ __('Wallet') }}
@endsection

@push('css-page')
<style>
    .wallet-btn {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        min-height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .wallet-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .wallet-btn:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none !important;
    }
    
    .wallet-btn.btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-color: #28a745;
        color: white;
    }
    
    .wallet-btn.btn-success:hover {
        background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
        border-color: #20c997;
    }
    
    .wallet-btn.btn-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        border-color: #dc3545;
        color: white;
    }
    
    .wallet-btn.btn-danger:hover {
        background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
        border-color: #c82333;
    }
    
    .animate__animated {
        animation-duration: 0.6s;
    }
    
    .animate__pulse {
        animation-name: pulse;
    }
    
    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .btn-group-sm .btn {
        transition: all 0.2s ease;
    }
    
    .btn-group-sm .btn:hover {
        transform: scale(1.1);
    }
    
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .wallet-status {
        position: absolute;
        top: 5px;
        right: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #28a745;
        border: 2px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    
    .wallet-status.inactive {
        background: #6c757d;
    }
    
    .wallet-status.error {
        background: #dc3545;
    }
    
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .alert-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }
</style>
@endpush

@section('title')
    {{ __('Wallet') }}
@endsection

@section('content')
    <!-- Alert Container for Notifications -->
    <div class="alert-container" id="alertContainer"></div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ __('Wallet Passes') }}</h5>
                            <p class="text-muted mb-0">
                                @if($user->isSuperAdmin())
                                    {{ __('Super Admin: Manage all wallet passes across the platform') }}
                                @else
                                    {{ __('Manage your Apple and Google Wallet passes') }}
                                @endif
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Super Admin Badge -->
                            @if($user->isSuperAdmin())
                                <span class="badge bg-primary d-flex align-items-center">
                                    <i class="fas fa-crown me-1"></i>
                                    {{ __('Super Admin') }}
                                </span>
                            @endif
                            <!-- Service Status Indicators -->
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $walletStatus['apple']['enabled'] && $walletStatus['apple']['configured'] ? 'bg-success' : 'bg-secondary' }} d-flex align-items-center" data-wallet="apple">
                                    <i class="fab fa-apple me-1"></i>
                                    {{ __('Apple') }}
                                </span>
                                <span class="badge {{ $walletStatus['google']['enabled'] && $walletStatus['google']['configured'] ? 'bg-success' : 'bg-secondary' }} d-flex align-items-center" data-wallet="google">
                                    <i class="fab fa-google me-1"></i>
                                    {{ __('Google') }}
                                </span>
                            </div>
                            {{-- Test Wallet button removed --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($businesses->count() > 0)
                        <!-- Wallet Service Status Alert -->
                        @if((!($walletStatus['apple']['enabled'] && $walletStatus['apple']['configured']) || !($walletStatus['google']['enabled'] && $walletStatus['google']['configured'])) && Auth::user()->type != 'super admin')
                            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>{{ __('Wallet Services Notice:') }}</strong>
                                @if(!($walletStatus['apple']['enabled'] && $walletStatus['apple']['configured']) && !($walletStatus['google']['enabled'] && $walletStatus['google']['configured']))
                                    {{ __('Apple Wallet and Google Wallet services are not configured. Please contact support to enable wallet functionality.') }}
                                @elseif(!($walletStatus['apple']['enabled'] && $walletStatus['apple']['configured']))
                                    {{ __('Apple Wallet service is not configured. Google Wallet is available.') }}
                                @else
                                    {{ __('Google Wallet service is not configured. Apple Wallet is available.') }}
                                @endif
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            @foreach($businesses as $business)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm position-relative">
                                        <div class="card-body text-center p-4">
                                            <!-- Business Logo/Image -->
                                            <div class="mb-3">
                                                @php
                                                    $logo = \App\Models\Utility::get_file('card_logo/');
                                                @endphp
                                                @if($business->logo)
                                                    <img src="{{ $logo . '/' . $business->logo }}" 
                                                         alt="{{ $business->title }}" 
                                                         class="rounded-circle mx-auto d-block"
                                                         style="width: 80px; height: 80px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle mx-auto d-block bg-light d-flex align-items-center justify-content-center"
                                                         style="width: 80px; height: 80px;">
                                                        <i class="fas fa-briefcase fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Business Name -->
                                            <h6 class="card-title mb-1 fw-bold">{{ $business->title }}</h6>
                                            
                                            <!-- Business Designation -->
                                            <p class="text-muted small mb-3">
                                                {{ $business->designation ?? __('Business') }}
                                                @if($user->isSuperAdmin() && $business->created_by != $user->id)
                                                    <br><small class="text-info">
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ __('Owned by:') }} {{ \App\Models\User::find($business->created_by)->name ?? __('Unknown User') }}
                                                    </small>
                                                @endif
                                            </p>
                                            
                                            <!-- Wallet Buttons -->
                                            <div class="d-grid gap-2">
                                                <!-- Apple Wallet Button -->
                                                <button type="button" 
                                                        class="btn {{ isset($walletPasses[$business->id]['apple']) ? 'btn-success' : ($walletStatus['apple']['enabled'] && $walletStatus['apple']['configured'] ? 'btn-outline-dark' : 'btn-outline-dark') }} btn-sm wallet-btn"
                                                        id="apple-btn-{{ $business->id }}"
                                                        data-business-id="{{ $business->id }}"
                                                        data-business-name="{{ $business->title }}"
                                                        data-wallet-type="apple"
                                                        onclick="handleWalletAction(this)"
                                                        @if(isset($walletPasses[$business->id]['apple'])) disabled @endif>
                                                    <i class="fab fa-apple me-1"></i>
                                                    <span id="apple-text-{{ $business->id }}">
                                                        @if(isset($walletPasses[$business->id]['apple']))
                                                            <i class="fas fa-check me-1"></i> {{ __('Added') }}
                                                        @else
                                                            {{ __('Apple') }}
                                                        @endif
                                                    </span>
                                                </button>
                                                
                                                <!-- Google Wallet Button -->
                                                <button type="button" 
                                                        class="btn {{ isset($walletPasses[$business->id]['google']) ? 'btn-success' : ($walletStatus['google']['enabled'] && $walletStatus['google']['configured'] ? 'btn-outline-success' : 'btn-outline-secondary') }} btn-sm wallet-btn"
                                                        id="google-btn-{{ $business->id }}"
                                                        data-business-id="{{ $business->id }}"
                                                        data-business-name="{{ $business->title }}"
                                                        data-wallet-type="google"
                                                        onclick="handleWalletAction(this)"
                                                        @if(isset($walletPasses[$business->id]['google']) || !($walletStatus['google']['enabled'] && $walletStatus['google']['configured'])) disabled @endif>
                                                    <i class="fab fa-google me-1"></i>
                                                    <span id="google-text-{{ $business->id }}">
                                                        @if(isset($walletPasses[$business->id]['google']))
                                                            <i class="fas fa-check me-1"></i> {{ __('Added') }}
                                                        @elseif(!($walletStatus['google']['enabled'] && $walletStatus['google']['configured']))
                                                            <i class="fas fa-times me-1"></i> {{ __('Unavailable') }}
                                                        @else
                                                            {{ __('Google') }}
                                                        @endif
                                                    </span>
                                                </button>
                                                
                                                <!-- Share Buttons (only show if wallet passes exist) -->
                                                @if(isset($walletPasses[$business->id]))
                                                    <div class="btn-group btn-group-sm mt-2" role="group">
                                                        <button type="button" class="btn btn-outline-info btn-sm" 
                                                                onclick="shareViaWhatsApp('{{ $business->title }}', '{{ url('/' . $business->slug) }}')"
                                                                title="{{ __('Share via WhatsApp') }}">
                                                            <i class="fab fa-whatsapp"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                                onclick="shareViaAirDrop('{{ $business->title }}', '{{ url('/' . $business->slug) }}')"
                                                                title="{{ __('Share via AirDrop') }}">
                                                            <i class="fas fa-share-alt"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                                onclick="shareViaEmail('{{ $business->title }}', '{{ url('/' . $business->slug) }}')"
                                                                title="{{ __('Share via Email') }}">
                                                            <i class="fas fa-envelope"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Status Indicators -->
                                            @if(isset($walletPasses[$business->id]))
                                                <div class="mt-3">
                                                    <small class="text-muted">
                                                        @if(isset($walletPasses[$business->id]['apple']))
                                                            <i class="fab fa-apple text-dark"></i>
                                                        @endif
                                                        @if(isset($walletPasses[$business->id]['google']))
                                                            <i class="fab fa-google text-success"></i>
                                                        @endif
                                                        {{ __('Wallet passes available') }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-wallet fa-4x text-muted"></i>
                            </div>
                            <h5 class="text-muted">{{ __('No businesses found') }}</h5>
                            <p class="text-muted">{{ __('Create a business card first to add it to your wallet.') }}</p>
                            <a href="{{ route('business.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i>
                                {{ __('Create Business Card') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">{{ __('Loading...') }}</span>
                    </div>
                    <h6 id="loadingMessage">{{ __('Generating wallet pass...') }}</h6>
                    <p class="text-muted small">{{ __('Please wait while we create your wallet pass.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ __('Wallet Pass Ready!') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-wallet fa-2x text-success"></i>
                        </div>
                    </div>
                    <h5 id="successTitle" class="mb-3">{{ __('Wallet Pass Generated!') }}</h5>
                    <p id="successMessage" class="text-muted mb-4">{{ __('Your wallet pass has been created successfully.') }}</p>
                    <div id="walletActions">
                        <!-- Actions will be dynamically added here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('Error') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <div class="mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                    </div>
                    <h5 id="errorTitle" class="mb-3">{{ __('Something went wrong') }}</h5>
                    <p id="errorMessage" class="text-muted mb-4">{{ __('An error occurred while generating your wallet pass.') }}</p>
                    <div class="d-grid">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            {{ __('Close') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
<script>
// Global variables
let isProcessing = false;

// Main wallet action handler
function handleWalletAction(button) {
    if (isProcessing) {
        showNotification('warning', '{{ __("Please wait, another action is in progress...") }}');
        return;
    }

    const businessId = button.dataset.businessId;
    const businessName = button.dataset.businessName;
    const walletType = button.dataset.walletType;
    const isSuperAdmin = {{ $user->isSuperAdmin() ? 'true' : 'false' }};

    // Check if this is a demo mode (services not configured)
    const isDemoMode = !document.querySelector(`.badge.bg-success[data-wallet="${walletType}"]`);
    
    if (isDemoMode) {
        showDemoMode(walletType, businessName, isSuperAdmin);
        return;
    }

    // Check wallet service status first
    checkWalletServiceStatus(walletType)
        .then(() => {
            // Service is available, proceed with generation
            generateWalletPass(businessId, businessName, walletType, isSuperAdmin);
        })
        .catch((error) => {
            // Service not available, show error
            showErrorModal('{{ __("Service Unavailable") }}', error);
        });
}

// Show demo mode when services are not configured
function showDemoMode(walletType, businessName, isSuperAdmin) {
    const adminText = isSuperAdmin ? '{{ __("(Super Admin Access)") }}' : '';
    showNotification('info', `{{ __("Demo Mode:") }} ${walletType === 'apple' ? 'Apple' : 'Google'} {{ __("Wallet would be generated for") }} ${businessName} ${adminText}`);
    
    // Show a demo success modal
    showSuccessModal(
        `${walletType === 'apple' ? 'Apple' : 'Google'} {{ __('Wallet Pass Demo') }}`,
        `{{ __('This is a demonstration of how the') }} ${walletType === 'apple' ? 'Apple' : 'Google'} {{ __('Wallet pass would work.') }} ${adminText}`,
        '#',
        walletType
    );
}

// Generate wallet pass
function generateWalletPass(businessId, businessName, walletType, isSuperAdmin) {
    // Update button to loading state
    updateButtonToLoading(businessId, walletType);
    showLoadingModal(`{{ __('Generating') }} ${walletType === 'apple' ? 'Apple' : 'Google'} {{ __('Wallet pass for') }} ${businessName}...`);
    
    isProcessing = true;

    $.ajax({
        url: `/wallet/${walletType}/${businessId}`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        timeout: 30000, // 30 seconds timeout
        success: function(response) {
            hideLoadingModal();
            isProcessing = false;
            
            if (response.success) {
                // Update button to success state
                updateButtonToSuccess(businessId, walletType);
                const adminText = isSuperAdmin ? '{{ __("(Super Admin Access)") }}' : '';
                showSuccessModal(
                    `${walletType === 'apple' ? 'Apple' : 'Google'} {{ __('Wallet Pass Ready!') }}`,
                    response.message + ' ' + adminText,
                    response.download_url || response.save_url,
                    walletType
                );
                showNotification('success', `${walletType === 'apple' ? 'Apple' : 'Google'} {{ __('Wallet pass generated successfully!') }} ${adminText}`);
            } else {
                // Reset button to original state
                updateButtonToOriginal(businessId, walletType);
                showErrorModal(
                    '{{ __("Generation Failed") }}',
                    response.error || '{{ __("An error occurred while generating the pass.") }}'
                );
            }
        },
        error: function(xhr, status, error) {
            hideLoadingModal();
            isProcessing = false;
            
            // Reset button to original state
            updateButtonToOriginal(businessId, walletType);
            
            let errorMessage = '{{ __("An error occurred") }}';
            
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            } else if (status === 'timeout') {
                errorMessage = '{{ __("Request timed out. Please try again.") }}';
            } else if (xhr.status === 403) {
                errorMessage = '{{ __("Access denied. Please check your permissions.") }}';
            } else if (xhr.status === 500) {
                errorMessage = '{{ __("Server error. Please try again later.") }}';
            } else if (xhr.status === 503) {
                errorMessage = '{{ __("Service temporarily unavailable. Please try again later.") }}';
            }
            
            showErrorModal('{{ __("Error") }}', errorMessage);
            console.error('Wallet action error:', {xhr, status, error});
        }
    });
}

{{-- Test wallet functionality removed --}}

// Check wallet service status before generating pass
function checkWalletServiceStatus(walletType) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: '{{ route("wallet.status") }}',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    const status = response.status;
                    if (walletType === 'apple') {
                        if (status.apple.enabled && status.apple.configured) {
                            resolve(true);
                        } else {
                            reject('{{ __("Apple Wallet is not properly configured. Please contact support.") }}');
                        }
                    } else if (walletType === 'google') {
                        if (status.google.enabled && status.google.configured) {
                            resolve(true);
                        } else {
                            reject('{{ __("Google Wallet is not properly configured. Please contact support.") }}');
                        }
                    }
                } else {
                    reject(response.error || '{{ __("Unable to check wallet service status.") }}');
                }
            },
            error: function(xhr) {
                let error = '{{ __("Unable to check wallet service status.") }}';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    error = xhr.responseJSON.error;
                }
                reject(error);
            }
        });
    });
}

// Modal functions
function showLoadingModal(message) {
    $('#loadingMessage').text(message);
    $('#loadingModal').modal('show');
}

function hideLoadingModal() {
    $('#loadingModal').modal('hide');
}

function showSuccessModal(title, message, actionUrl, walletType) {
    $('#successTitle').text(title);
    $('#successMessage').text(message);
    
    let actionsHtml = '';
    if (walletType === 'apple') {
        actionsHtml = `
            <a href="${actionUrl}" class="btn btn-dark btn-lg mb-2" style="width: 100%;">
                <i class="fab fa-apple me-2"></i>
                {{ __('Download Apple Wallet Pass') }}
            </a>
        `;
    } else if (walletType === 'google') {
        actionsHtml = `
            <a href="${actionUrl}" class="btn btn-success btn-lg mb-2" target="_blank" style="width: 100%;">
                <i class="fab fa-google me-2"></i>
                {{ __('Open in Google Wallet') }}
            </a>
        `;
    }
    
    actionsHtml += `
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="shareWalletPass('${walletType}')">
                <i class="fas fa-share-alt me-2"></i>
                {{ __('Share with Others') }}
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                <i class="fas fa-times me-2"></i>
                {{ __('Close') }}
            </button>
        </div>
    `;
    
    $('#walletActions').html(actionsHtml);
    $('#successModal').modal('show');
}

function showErrorModal(title, message) {
    $('#errorTitle').text(title);
    $('#errorMessage').text(message);
    $('#errorModal').modal('show');
}

// Notification system
function showNotification(type, message) {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();
    
    let alertClass = 'alert-success';
    let icon = 'fas fa-check-circle';
    
    switch(type) {
        case 'error':
            alertClass = 'alert-danger';
            icon = 'fas fa-exclamation-circle';
            break;
        case 'warning':
            alertClass = 'alert-warning';
            icon = 'fas fa-exclamation-triangle';
            break;
        case 'info':
            alertClass = 'alert-info';
            icon = 'fas fa-info-circle';
            break;
    }
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" id="${alertId}" role="alert">
            <i class="${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Button state management functions
function updateButtonToLoading(businessId, walletType) {
    const btn = $(`#${walletType}-btn-${businessId}`);
    const textSpan = $(`#${walletType}-text-${businessId}`);
    
    btn.prop('disabled', true);
    btn.removeClass('btn-outline-dark btn-outline-success btn-secondary btn-danger').addClass('btn-secondary');
    textSpan.html('<span class="loading-spinner me-1"></span> {{ __("Generating...") }}');
}

function updateButtonToSuccess(businessId, walletType) {
    const btn = $(`#${walletType}-btn-${businessId}`);
    const textSpan = $(`#${walletType}-text-${businessId}`);
    
    btn.prop('disabled', true);
    btn.removeClass('btn-outline-dark btn-outline-success btn-secondary btn-danger').addClass('btn-success');
    textSpan.html('<i class="fas fa-check me-1"></i> {{ __("Added") }}');
    
    // Add a subtle animation
    btn.addClass('animate__animated animate__pulse');
    setTimeout(() => {
        btn.removeClass('animate__animated animate__pulse');
    }, 1000);
}

function updateButtonToOriginal(businessId, walletType) {
    const btn = $(`#${walletType}-btn-${businessId}`);
    const textSpan = $(`#${walletType}-text-${businessId}`);
    
    btn.prop('disabled', false);
    
    if (walletType === 'apple') {
        btn.removeClass('btn-secondary btn-success btn-danger').addClass('btn-outline-dark');
        textSpan.html('{{ __("Apple") }}');
    } else {
        btn.removeClass('btn-secondary btn-success btn-danger').addClass('btn-outline-success');
        textSpan.html('{{ __("Google") }}');
    }
}

// Sharing functions
function shareWalletPass(walletType) {
    const businessName = '{{ Auth::user()->name }}';
    const message = `I just added my business card to ${walletType === 'apple' ? 'Apple' : 'Google'} Wallet! Check it out: {{ url('/') }}`;
    
    if (navigator.share) {
        navigator.share({
            title: `${businessName} - Digital Business Card`,
            text: message,
            url: '{{ url('/') }}'
        });
    } else {
        // Fallback to copying to clipboard
        const tempInput = document.createElement('textarea');
        tempInput.value = message;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        showNotification('success', '{{ __("Share message copied to clipboard!") }}');
    }
}

function shareViaWhatsApp(businessName, cardUrl) {
    const message = `Check out my digital business card: ${businessName}\n\n${cardUrl}\n\nAdd it to your Apple or Google Wallet for easy access!`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function shareViaAirDrop(businessName, cardUrl) {
    const message = `Check out my digital business card: ${businessName}\n\n${cardUrl}\n\nAdd it to your Apple or Google Wallet for easy access!`;
    
    if (navigator.share) {
        navigator.share({
            title: `${businessName} - Digital Business Card`,
            text: message,
            url: cardUrl
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        const tempInput = document.createElement('textarea');
        tempInput.value = message;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        
        showNotification('success', '{{ __("Message copied to clipboard! You can now paste it in any app.") }}');
    }
}

function shareViaEmail(businessName, cardUrl) {
    const subject = `Check out ${businessName}'s Digital Business Card`;
    const body = `Hi there,\n\nI'd like to share my digital business card with you:\n\nBusiness: ${businessName}\nCard URL: ${cardUrl}\n\nYou can add this to your Apple or Google Wallet for easy access to my contact information.\n\nBest regards,\n{{ Auth::user()->name }}`;
    
    const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoUrl;
}

// Auto-refresh page after successful wallet pass generation
$('#successModal').on('hidden.bs.modal', function () {
    setTimeout(function() {
        location.reload();
    }, 1000);
});

// Initialize on page load
$(document).ready(function() {
    // Check if CSRF token is available
    if (!document.querySelector('meta[name="csrf-token"]')) {
        console.warn('CSRF token not found. Adding it to head...');
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }
    
    // Add error handling for AJAX requests
    $(document).ajaxError(function(event, xhr, settings, error) {
        console.error('AJAX Error:', {event, xhr, settings, error});
    });
});
</script>
@endpush 