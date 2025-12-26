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
    }
    
    .wallet-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .wallet-btn:disabled {
        opacity: 0.8;
        cursor: not-allowed;
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
</style>
@endpush
@section('title')
    {{ __('Wallet') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ __('Wallet Passes') }}</h5>
                            <p class="text-muted mb-0">{{ __('Manage your Apple and Google Wallet passes') }}</p>
                        </div>
                        <div>
                            <button type="button" class="btn btn-info btn-sm" onclick="testModal()">
                                <i class="fas fa-pencil"></i>
                                {{ __('Test Modal') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($businesses->count() > 0)
                        <div class="row">
                            @foreach($businesses as $business)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card h-100 border-0 shadow-sm">
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
                                            <p class="text-muted small mb-3">{{ $business->designation ?? __('Business') }}</p>
                                            
                                            <!-- Wallet Buttons -->
                                            <div class="d-grid gap-2">
                                                <!-- Apple Wallet Button -->
                                                <button type="button" 
                                                        class="btn {{ isset($walletPasses[$business->id]['apple']) ? 'btn-success' : 'btn-outline-dark' }} btn-sm wallet-btn"
                                                        id="apple-btn-{{ $business->id }}"
                                                        onclick="handleAppleWallet({{ $business->id }}, '{{ $business->title }}')"
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
                                                        class="btn {{ isset($walletPasses[$business->id]['google']) ? 'btn-success' : 'btn-outline-success' }} btn-sm wallet-btn"
                                                        id="google-btn-{{ $business->id }}"
                                                        onclick="handleGoogleWallet({{ $business->id }}, '{{ $business->title }}')"
                                                        @if(isset($walletPasses[$business->id]['google'])) disabled @endif>
                                                    <i class="fab fa-google me-1"></i>
                                                    <span id="google-text-{{ $business->id }}">
                                                        @if(isset($walletPasses[$business->id]['google']))
                                                            <i class="fas fa-check me-1"></i> {{ __('Added') }}
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
@endsection

@push('script-page')
<script>
function handleAppleWallet(businessId, businessName) {
    // Update button to loading state
    updateButtonToLoading(businessId, 'apple');
    showLoadingModal(`{{ __('Generating Apple Wallet pass for') }} ${businessName}...`);
    
    $.ajax({
        url: `/wallet/apple/${businessId}`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoadingModal();
            if (response.success) {
                // Update button to success state
                updateButtonToSuccess(businessId, 'apple');
                showSuccessModal(
                    '{{ __("Apple Wallet Pass Ready!") }}',
                    response.message,
                    response.download_url,
                    'apple'
                );
                // Show success alert
                showAlert('success', '{{ __("Apple Wallet pass generated successfully!") }}');
            } else {
                // Reset button to original state
                updateButtonToOriginal(businessId, 'apple');
                showAlert('error', response.error || '{{ __("An error occurred") }}');
            }
        },
        error: function(xhr) {
            hideLoadingModal();
            // Reset button to original state
            updateButtonToOriginal(businessId, 'apple');
            let error = '{{ __("An error occurred") }}';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                error = xhr.responseJSON.error;
            }
            showAlert('error', error);
        }
    });
}

function handleGoogleWallet(businessId, businessName) {
    // Update button to loading state
    updateButtonToLoading(businessId, 'google');
    showLoadingModal(`{{ __('Generating Google Wallet pass for') }} ${businessName}...`);
    
    $.ajax({
        url: `/wallet/google/${businessId}`,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoadingModal();
            if (response.success) {
                // Update button to success state
                updateButtonToSuccess(businessId, 'google');
                showSuccessModal(
                    '{{ __("Google Wallet Pass Ready!") }}',
                    response.message,
                    response.save_url,
                    'google'
                );
                // Show success alert
                showAlert('success', '{{ __("Google Wallet pass generated successfully!") }}');
            } else {
                // Reset button to original state
                updateButtonToOriginal(businessId, 'google');
                showAlert('error', response.error || '{{ __("An error occurred") }}');
            }
        },
        error: function(xhr) {
            hideLoadingModal();
            // Reset button to original state
            updateButtonToOriginal(businessId, 'google');
            let error = '{{ __("An error occurred") }}';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                error = xhr.responseJSON.error;
            }
            showAlert('error', error);
        }
    });
}

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
        
        showAlert('success', '{{ __("Share message copied to clipboard!") }}');
    }
}

function testModal() {
    showSuccessModal(
        '{{ __("Test Modal") }}',
        '{{ __("This is a test modal for demonstration purposes.") }}',
        '#',
        'apple'
    );
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
    
    $('.card-body').prepend(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Button state management functions
function updateButtonToLoading(businessId, walletType) {
    const btn = $(`#${walletType}-btn-${businessId}`);
    const textSpan = $(`#${walletType}-text-${businessId}`);
    
    btn.prop('disabled', true);
    btn.removeClass('btn-outline-dark btn-outline-success btn-success').addClass('btn-secondary');
    textSpan.html('<i class="fas fa-spinner fa-spin me-1"></i> {{ __("Generating...") }}');
}

function updateButtonToSuccess(businessId, walletType) {
    const btn = $(`#${walletType}-btn-${businessId}`);
    const textSpan = $(`#${walletType}-text-${businessId}`);
    
    btn.prop('disabled', true);
    btn.removeClass('btn-outline-dark btn-outline-success btn-secondary').addClass('btn-success');
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
        btn.removeClass('btn-secondary btn-success').addClass('btn-outline-dark');
        textSpan.html('{{ __("Apple") }}');
    } else {
        btn.removeClass('btn-secondary btn-success').addClass('btn-outline-success');
        textSpan.html('{{ __("Google") }}');
    }
}

// Auto-refresh page after successful wallet pass generation
$('#successModal').on('hidden.bs.modal', function () {
    setTimeout(function() {
        location.reload();
    }, 1000);
});

// Sharing Functions
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
        
        showAlert('success', 'Message copied to clipboard! You can now paste it in any app.');
    }
}

function shareViaEmail(businessName, cardUrl) {
    const subject = `Check out ${businessName}'s Digital Business Card`;
    const body = `Hi there,\n\nI'd like to share my digital business card with you:\n\nBusiness: ${businessName}\nCard URL: ${cardUrl}\n\nYou can add this to your Apple or Google Wallet for easy access to my contact information.\n\nBest regards,\n{{ Auth::user()->name }}`;
    
    const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoUrl;
}
</script>
@endpush 