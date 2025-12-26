@extends('layouts.app')
@section('title')
    {{ __('messages.subscription.payment') }}
@endsection
@section('content')
    <div class="container-fluid">
        @include('flash::message')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <i class="fas fa-wallet me-2 text-warning"></i>
                                    Wallet Integration Add-On
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card bg-light border-warning">
                                            <div class="card-body text-center">
                                                <h5 class="text-warning mb-3">
                                                    <i class="fas fa-wallet me-2"></i>Wallet Integration
                                                </h5>
                                                <h2 class="text-success mb-3">$1.00 USD</h2>
                                                <p class="text-muted mb-4">One-time purchase</p>
                                                
                                                <div class="text-start">
                                                    <div class="mb-3">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        <small>Add to Apple Wallet</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        <small>Add to Google Wallet</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        <small>Tap-to-share via NFC</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        <small>QR code scanning</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                        <small>Professional appearance</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                    <i class="fas fa-credit-card me-2"></i>Payment Method
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <form id="walletAddonPaymentForm">
                                                    <input type="hidden" name="planId" value="wallet-addon">
                                                    <input type="hidden" name="price" value="1.00">
                                                    <input type="hidden" name="currency" value="USD">
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Payment Method</label>
                                                        <select class="form-select" id="paymentType" name="payment_type" required>
                                                            <option value="">Choose payment method</option>
                                                            @foreach($paymentTypes as $paymentType)
                                                                <option value="{{ $paymentType['name'] }}">
                                                                    {{ $paymentType['name'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-warning btn-lg">
                                                            <i class="fas fa-lock me-2"></i>Pay $1.00 USD
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-info-circle me-2"></i>What You Get
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="fab fa-apple me-2"></i>Apple Wallet
                                    </h6>
                                    <small class="text-muted">
                                        Add your vCard to Apple Wallet for easy sharing and professional presentation.
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="fab fa-google me-2"></i>Google Wallet
                                    </h6>
                                    <small class="text-muted">
                                        Add your vCard to Google Wallet for seamless integration with Android devices.
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="fas fa-wifi me-2"></i>NFC Sharing
                                    </h6>
                                    <small class="text-muted">
                                        Tap your phone on another device to instantly share your vCard.
                                    </small>
                                </div>
                                
                                <div class="mb-3">
                                    <h6 class="text-primary">
                                        <i class="fas fa-qrcode me-2"></i>QR Code
                                    </h6>
                                    <small class="text-muted">
                                        Generate QR codes that link directly to your vCard profile.
                                    </small>
                                </div>
                                
                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-lightbulb me-2"></i>
                                        <strong>Pro Tip:</strong> This is a one-time purchase. Once activated, wallet integration will be available for all your vCards.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#walletAddonPaymentForm').on('submit', function(e) {
        e.preventDefault();
        
        const paymentType = $('#paymentType').val();
        if (!paymentType) {
            alert('Please select a payment method.');
            return;
        }
        
        const formData = {
            planId: 'wallet-addon',
            price: 1.00,
            currency: 'USD',
            payment_type: paymentType,
            is_wallet_addon: true
        };
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...').prop('disabled', true);
        
        // Handle different payment gateways
        if (paymentType.toLowerCase() === 'stripe') {
            // Redirect to Stripe payment
            window.location.href = '{{ route("choose.payment.type", "wallet-addon") }}?payment_type=stripe';
        } else if (paymentType.toLowerCase() === 'paypal') {
            // Redirect to PayPal payment
            window.location.href = '{{ route("choose.payment.type", "wallet-addon") }}?payment_type=paypal';
        } else if (paymentType.toLowerCase() === 'paystack') {
            // Redirect to Paystack payment
            window.location.href = '{{ route("choose.payment.type", "wallet-addon") }}?payment_type=paystack';
        } else if (paymentType.toLowerCase() === 'razorpay') {
            // Redirect to Razorpay payment
            window.location.href = '{{ route("choose.payment.type", "wallet-addon") }}?payment_type=razorpay';
        } else {
            // For other payment methods, use direct processing
            $.post('{{ route("purchase-subscription") }}', formData)
                .done(function(result) {
                    if (result.status) {
                        // Success - redirect to subscription page
                        window.location.href = '{{ route("subscription.index") }}';
                    } else {
                        alert(result.message || 'Payment failed. Please try again.');
                    }
                })
                .fail(function(error) {
                    alert(error.responseJSON?.message || 'Payment failed. Please try again.');
                })
                .always(function() {
                    submitBtn.html(originalText).prop('disabled', false);
                });
        }
    });
});
</script>
@endpush 