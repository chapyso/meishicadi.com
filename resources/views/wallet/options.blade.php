@extends('layouts.admin')
@section('page-title')
    {{ __('Add to Wallet') }}
@endsection
@section('title')
    {{ __('Add to Wallet') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Add Your Business Card to Digital Wallet') }}</h5>
                    <p class="text-muted mb-0">{{ __('Make your business card easily accessible on your phone with Apple Wallet or Google Wallet.') }}</p>
                </div>
                <div class="card-body">
                    <!-- Alert Container for Messages -->
                    <div id="alert-container"></div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fab fa-apple fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">{{ __('Apple Wallet') }}</h5>
                                    <p class="card-text">{{ __('Add your business card to Apple Wallet for easy access on iPhone and Apple Watch.') }}</p>
                                    
                                    @if(isset($existingPasses['apple']))
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            {{ __('Apple Wallet pass already generated!') }}
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('wallet.apple.download', $existingPasses['apple']->pass_id) }}" 
                                               class="btn btn-primary">
                                                <i class="fas fa-download"></i>
                                                {{ __('Download Pass') }}
                                            </a>
                                            <button type="button" class="btn btn-outline-primary dropdown-toggle dropdown-toggle-split" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="shareViaWhatsApp('apple')">
                                                    <i class="fab fa-whatsapp text-success"></i> {{ __('Share via WhatsApp') }}
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="shareViaAirDrop('apple')">
                                                    <i class="fas fa-share-alt text-primary"></i> {{ __('Share via AirDrop') }}
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="shareViaEmail('apple')">
                                                    <i class="fas fa-envelope text-info"></i> {{ __('Share via Email') }}
                                                </a></li>
                                            </ul>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-primary" id="apple-wallet-btn" onclick="generateApplePass()">
                                            <i class="fab fa-apple"></i>
                                            <span id="apple-btn-text">{{ __('Add to Apple Wallet') }}</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fab fa-google fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title">{{ __('Google Wallet') }}</h5>
                                    <p class="card-text">{{ __('Add your business card to Google Wallet for easy access on Android devices.') }}</p>
                                    
                                    @if(isset($existingPasses['google']))
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            {{ __('Google Wallet pass already generated!') }}
                                        </div>
                                        <div class="btn-group" role="group">
                                            <a href="{{ $googleWalletService->getPassSaveUrl($existingPasses['google']) }}" 
                                               class="btn btn-success" target="_blank">
                                                <i class="fas fa-external-link-alt"></i>
                                                {{ __('Open in Google Wallet') }}
                                            </a>
                                            <button type="button" class="btn btn-outline-success dropdown-toggle dropdown-toggle-split" 
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="shareViaWhatsApp('google')">
                                                    <i class="fab fa-whatsapp text-success"></i> {{ __('Share via WhatsApp') }}
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="shareViaAirDrop('google')">
                                                    <i class="fas fa-share-alt text-primary"></i> {{ __('Share via AirDrop') }}
                                                </a></li>
                                                <li><a class="dropdown-item" href="#" onclick="shareViaEmail('google')">
                                                    <i class="fas fa-envelope text-info"></i> {{ __('Share via Email') }}
                                                </a></li>
                                            </ul>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-success" id="google-wallet-btn" onclick="generateGooglePass()">
                                            <i class="fab fa-google"></i>
                                            <span id="google-btn-text">{{ __('Add to Google Wallet') }}</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle text-info"></i>
                                        {{ __('About Your Business Card') }}
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>{{ __('Business Name:') }}</strong><br>
                                            {{ $business->title }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Designation:') }}</strong><br>
                                            {{ $business->designation ?? __('Not specified') }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Card URL:') }}</strong><br>
                                            <a href="{{ url('/' . $business->slug) }}" target="_blank">
                                                {{ url('/' . $business->slug) }}
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>{{ __('Status:') }}</strong><br>
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-lightbulb"></i>
                                    {{ __('Pro Tips') }}
                                </h6>
                                <ul class="mb-0">
                                    <li>{{ __('Your wallet pass will include your business name, contact information, and a QR code linking to your digital card.') }}</li>
                                    <li>{{ __('The pass will automatically update when you modify your business information.') }}</li>
                                    <li>{{ __('You can share the wallet pass with clients for easy access to your contact details.') }}</li>
                                    <li>{{ __('The pass will expire after one year and can be renewed anytime.') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 id="loadingModalLabel">Generating Wallet Pass...</h5>
                    <p class="text-muted">Please wait while we create your digital wallet pass. This may take a few moments.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successModalLabel">
                        <i class="fas fa-check-circle"></i> Wallet Pass Generated Successfully!
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-wallet fa-3x text-success"></i>
                    </div>
                    <p id="successMessage"></p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>What's Next?</strong><br>
                        Your wallet pass has been generated and an email has been sent to your registered email address with download instructions.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="downloadBtn" style="display: none;">
                        <i class="fas fa-download"></i> Download Now
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-page')
<script>
let currentWalletType = '';
let downloadUrl = '';

function generateApplePass() {
    currentWalletType = 'apple';
    showLoadingModal('Generating Apple Wallet Pass...');
    generateWalletPass('{{ route("wallet.apple.generate", $business->id) }}');
}

function generateGooglePass() {
    currentWalletType = 'google';
    showLoadingModal('Generating Google Wallet Pass...');
    generateWalletPass('{{ route("wallet.google.generate", $business->id) }}');
}

function generateWalletPass(url) {
    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            hideLoadingModal();
            if (response.success) {
                downloadUrl = response.download_url || response.save_url;
                showSuccessModal(response.message, downloadUrl);
                showAlert('success', response.message);
                
                // Update button state
                updateButtonState(currentWalletType, true);
                
                // Reload page after 3 seconds to show new buttons
                setTimeout(function() {
                    location.reload();
                }, 3000);
            } else {
                showAlert('error', response.error || '{{ __("An error occurred") }}');
            }
        },
        error: function(xhr) {
            hideLoadingModal();
            let error = '{{ __("An error occurred") }}';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                error = xhr.responseJSON.error;
            }
            showAlert('error', error);
        }
    });
}

function showLoadingModal(message) {
    $('#loadingModalLabel').text(message);
    $('#loadingModal').modal('show');
    
    // Disable buttons
    $('#apple-wallet-btn, #google-wallet-btn').prop('disabled', true);
}

function hideLoadingModal() {
    $('#loadingModal').modal('hide');
    
    // Re-enable buttons
    $('#apple-wallet-btn, #google-wallet-btn').prop('disabled', false);
}

function showSuccessModal(message, url) {
    $('#successMessage').text(message);
    $('#successModal').modal('show');
    
    if (url) {
        $('#downloadBtn').show().off('click').on('click', function() {
            if (currentWalletType === 'apple') {
                window.location.href = url;
            } else {
                window.open(url, '_blank');
            }
        });
    } else {
        $('#downloadBtn').hide();
    }
}

function updateButtonState(walletType, generated) {
    const btn = walletType === 'apple' ? $('#apple-wallet-btn') : $('#google-wallet-btn');
    const btnText = walletType === 'apple' ? $('#apple-btn-text') : $('#google-btn-text');
    
    if (generated) {
        btn.removeClass('btn-primary btn-success').addClass('btn-secondary');
        btnText.text('Generated Successfully!');
        btn.prop('disabled', true);
    }
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
    
    $('#alert-container').html(alertHtml);
    
    // Auto-dismiss after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Sharing Functions
function shareViaWhatsApp(walletType) {
    const businessName = '{{ $business->title }}';
    const cardUrl = '{{ url("/" . $business->slug) }}';
    const message = `Check out my digital business card: ${businessName}\n\n${cardUrl}\n\nAdd it to your ${walletType === 'apple' ? 'Apple' : 'Google'} Wallet for easy access!`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function shareViaAirDrop(walletType) {
    const businessName = '{{ $business->title }}';
    const cardUrl = '{{ url("/" . $business->slug) }}';
    const message = `Check out my digital business card: ${businessName}\n\n${cardUrl}\n\nAdd it to your ${walletType === 'apple' ? 'Apple' : 'Google'} Wallet for easy access!`;
    
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

function shareViaEmail(walletType) {
    const businessName = '{{ $business->title }}';
    const cardUrl = '{{ url("/" . $business->slug) }}';
    const subject = `Check out ${businessName}'s Digital Business Card`;
    const body = `Hi there,\n\nI'd like to share my digital business card with you:\n\nBusiness: ${businessName}\nCard URL: ${cardUrl}\n\nYou can add this to your ${walletType === 'apple' ? 'Apple' : 'Google'} Wallet for easy access to my contact information.\n\nBest regards,\n{{ Auth::user()->name }}`;
    
    const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoUrl;
}
</script>
@endpush 