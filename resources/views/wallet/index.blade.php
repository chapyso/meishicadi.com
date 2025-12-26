@extends('layouts.admin')
@section('page-title')
    {{ __('Wallet') }}
@endsection

@push('css-page')
<style>
    .btn-loading {
        opacity: 0.7;
        cursor: not-allowed;
    }
    
    .btn-loading:hover {
        opacity: 0.7;
    }
    
    /* Ensure modal is visible */
    .modal-backdrop {
        z-index: 1040;
    }
    
    .modal {
        z-index: 1050;
    }
    
    /* Loading animation */
    .ti-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush
@section('title')
    {{ __('Wallet') }}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Wallet') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Wallet Passes') }}</h5>
                <small class="text-muted">{{ __('Manage your Apple and Google Wallet passes') }}</small>
                <button type="button" class="btn btn-sm btn-outline-info float-end" onclick="testModal()">
                    <i class="ti ti-test-pipe me-1"></i> Test Modal
                </button>
            </div>
            <div class="card-body">
                @if($businesses->count() > 0)
                    <div class="row">
                        @foreach($businesses as $business)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar me-3">
                                                <img src="{{ (!empty($business->logo) && file_exists(public_path('storage/card_logo/' . $business->logo))) ? asset('storage/card_logo/' . $business->logo) : asset('custom/img/logo-placeholder-image-2.png') }}" 
                                                     alt="{{ $business->title }}" 
                                                     class="rounded-circle" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $business->title }}</h6>
                                                <small class="text-muted">{{ $business->designation }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <button type="button" 
                                                        class="btn btn-outline-primary btn-sm w-100 generate-apple-wallet" 
                                                        data-business-id="{{ $business->id }}"
                                                        data-business-name="{{ $business->title }}">
                                                    <i class="ti ti-brand-apple me-1"></i>
                                                    Apple
                                                </button>
                                            </div>
                                            <div class="col-6">
                                                <button type="button" 
                                                        class="btn btn-outline-success btn-sm w-100 generate-google-wallet" 
                                                        data-business-id="{{ $business->id }}"
                                                        data-business-name="{{ $business->title }}">
                                                    <i class="ti ti-brand-google me-1"></i>
                                                    Google
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-credit-card" style="font-size: 4rem; color: #ccc;"></i>
                        <h5 class="mt-3">{{ __('No Business Cards Found') }}</h5>
                        <p class="text-muted">{{ __('Create a business card first to generate wallet passes.') }}</p>
                        <a href="{{ route('business.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>
                            {{ __('Create Business Card') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($walletPasses->count() > 0)
        <div class="col-xl-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Recent Wallet Passes') }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Business') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Generated') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($walletPasses as $pass)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar me-2">
                                                    <img src="{{ (!empty($pass->business->logo) && file_exists(public_path('storage/card_logo/' . $pass->business->logo))) ? asset('storage/card_logo/' . $pass->business->logo) : asset('custom/img/logo-placeholder-image-2.png') }}" 
                                                         alt="{{ $pass->business->title }}" 
                                                         class="rounded-circle" 
                                                         style="width: 30px; height: 30px; object-fit: cover;">
                                                </div>
                                                <span>{{ $pass->business->title }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($pass->pass_type == 'apple')
                                                <span class="badge bg-primary">
                                                    <i class="ti ti-brand-apple me-1"></i>
                                                    Apple
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="ti ti-brand-google me-1"></i>
                                                    Google
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $pass->email }}</td>
                                        <td>{{ $pass->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($pass->email_sent)
                                                <span class="badge bg-success">{{ __('Sent') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pass->pass_type == 'apple' && $pass->pass_file_path)
                                                <a href="{{ route('wallet.download.apple', $pass->id) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-download me-1"></i>
                                                    {{ __('Download') }}
                                                </a>
                                            @elseif($pass->pass_type == 'google')
                                                <a href="{{ route('wallet.google.link', $pass->id) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   target="_blank">
                                                    <i class="ti ti-external-link me-1"></i>
                                                    {{ __('View') }}
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Wallet Generation Modal -->
<div class="modal fade" id="walletModal" tabindex="-1" aria-labelledby="walletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="walletModalLabel">{{ __('Generate Wallet Pass') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="walletModalContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Upgrade Modal -->
@include('wallet.upgrade-modal')
@endsection

@push('script-page')
<script>
    $(document).ready(function() {
        console.log('Wallet page loaded, binding events...');
        
        // Handle Apple Wallet generation
        $(document).on('click', '.generate-apple-wallet', function(e) {
            e.preventDefault();
            console.log('Apple wallet button clicked');
            var businessId = $(this).data('business-id');
            var businessName = $(this).data('business-name');
            console.log('Business ID:', businessId, 'Name:', businessName);
            
            // Add immediate visual feedback
            $(this).addClass('btn-loading').prop('disabled', true);
            $(this).html('<i class="ti ti-loader ti-spin me-1"></i> Checking...');
            
            checkWalletPremiumAndGenerate(businessId, 'apple', businessName);
        });
        
        // Handle Google Wallet generation
        $(document).on('click', '.generate-google-wallet', function(e) {
            e.preventDefault();
            console.log('Google wallet button clicked');
            var businessId = $(this).data('business-id');
            var businessName = $(this).data('business-name');
            console.log('Business ID:', businessId, 'Name:', businessName);
            
            // Add immediate visual feedback
            $(this).addClass('btn-loading').prop('disabled', true);
            $(this).html('<i class="ti ti-loader ti-spin me-1"></i> Checking...');
            
            checkWalletPremiumAndGenerate(businessId, 'google', businessName);
        });

        // Handle upgrade button click
        $('#upgradeWalletBtn').on('click', function() {
            console.log('Upgrade button clicked');
            var button = $(this);
            var originalText = button.html();
            
            // Show loading state
            button.html('<i class="ti ti-loader ti-spin me-2"></i> Processing...');
            button.prop('disabled', true);
            
            // Make AJAX request to subscribe
            $.ajax({
                url: '{{ route("wallet.subscribe") }}',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log('Subscribe response:', response);
                    if (response.success) {
                        toastrs('{{ __("Success") }}', response.message, 'success');
                        $('#walletUpgradeModal').modal('hide');
                        // Reload page to reflect changes
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        toastrs('{{ __("Error") }}', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Subscribe error:', xhr, status, error);
                    var message = 'An error occurred while processing the upgrade.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    toastrs('{{ __("Error") }}', message, 'error');
                },
                complete: function() {
                    // Restore button state
                    button.html(originalText);
                    button.prop('disabled', false);
                }
            });
        });
    });

    function checkWalletPremiumAndGenerate(businessId, type, businessName) {
        console.log('Checking wallet premium for business:', businessId, type, businessName);
        
        // First check if user has premium access
        $.ajax({
            url: '{{ route("wallet.check-premium") }}',
            type: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Premium check response:', response);
                
                // Reset button state
                var button = type === 'apple' ? $('.generate-apple-wallet[data-business-id="' + businessId + '"]') : $('.generate-google-wallet[data-business-id="' + businessId + '"]');
                button.removeClass('btn-loading').prop('disabled', false);
                
                if (response.has_premium) {
                    console.log('User has premium, generating wallet pass');
                    button.html('<i class="ti ti-brand-' + (type === 'apple' ? 'apple' : 'google') + ' me-1"></i>' + (type === 'apple' ? 'Apple' : 'Google'));
                    // User has premium, proceed with wallet generation
                    generateWalletPass(businessId, type, businessName);
                } else {
                    console.log('User does not have premium, showing upgrade modal');
                    button.html('<i class="ti ti-brand-' + (type === 'apple' ? 'apple' : 'google') + ' me-1"></i>' + (type === 'apple' ? 'Apple' : 'Google'));
                    // User doesn't have premium, show upgrade modal
                    $('#walletUpgradeModal').modal('show');
                }
            },
            error: function(xhr, status, error) {
                console.error('Premium check error:', xhr, status, error);
                
                // Reset button state
                var button = type === 'apple' ? $('.generate-apple-wallet[data-business-id="' + businessId + '"]') : $('.generate-google-wallet[data-business-id="' + businessId + '"]');
                button.removeClass('btn-loading').prop('disabled', false);
                button.html('<i class="ti ti-brand-' + (type === 'apple' ? 'apple' : 'google') + ' me-1"></i>' + (type === 'apple' ? 'Apple' : 'Google'));
                
                // Show error message
                toastrs('{{ __("Error") }}', 'Unable to check premium status. Please try again.', 'error');
                
                // If check fails, show upgrade modal as fallback
                $('#walletUpgradeModal').modal('show');
            }
        });
    }

    function generateWalletPass(businessId, type, businessName) {
        var button = type === 'apple' ? $('.generate-apple-wallet[data-business-id="' + businessId + '"]') : $('.generate-google-wallet[data-business-id="' + businessId + '"]');
        var originalText = button.html();
        
        // Show loading state
        button.html('<i class="ti ti-loader ti-spin me-1"></i> Generating...');
        button.prop('disabled', true);
        
        // Make AJAX request
        $.ajax({
            url: `/wallet/generate/${type}/${businessId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastrs('{{ __("Success") }}', response.message, 'success');
                    // Reload page to show updated wallet passes
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    toastrs('{{ __("Error") }}', response.message, 'error');
                }
            },
            error: function(xhr) {
                var message = 'An error occurred while generating the wallet pass.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                toastrs('{{ __("Error") }}', message, 'error');
            },
            complete: function() {
                // Restore button state
                button.html(originalText);
                button.prop('disabled', false);
            }
        });
    }
    
    // Test function to check if modal is working
    function testModal() {
        console.log('Test modal function called');
        $('#walletUpgradeModal').modal('show');
    }
</script>
@endpush 