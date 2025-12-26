<div class="modal fade" id="walletUpgradeModal" tabindex="-1" aria-labelledby="walletUpgradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="walletUpgradeModalLabel">
                    <i class="ti ti-crown me-2"></i>
                    {{ __('Upgrade to Premium') }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-4">
                    <i class="ti ti-device-mobile" style="font-size: 4rem; color: #007bff;"></i>
                </div>
                
                <h4 class="mb-3">{{ __('Unlock Wallet Features') }}</h4>
                
                <p class="text-muted mb-4">
                    {{ __('This feature is available for premium users only. Upgrade now for just 1 BHD to unlock Apple & Google Wallet support.') }}
                </p>
                
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="text-center">
                            <i class="ti ti-brand-apple" style="font-size: 2rem; color: #000;"></i>
                            <p class="mb-0 mt-2"><strong>{{ __('Apple Wallet') }}</strong></p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <i class="ti ti-brand-google" style="font-size: 2rem; color: #4285f4;"></i>
                            <p class="mb-0 mt-2"><strong>{{ __('Google Wallet') }}</strong></p>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>{{ __('What you get:') }}</strong>
                    <ul class="mb-0 mt-2 text-start">
                        <li>{{ __('Add business cards to Apple Wallet') }}</li>
                        <li>{{ __('Add business cards to Google Wallet') }}</li>
                        <li>{{ __('Premium support for 1 year') }}</li>
                        <li>{{ __('Unlimited wallet pass generation') }}</li>
                    </ul>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" id="upgradeWalletBtn">
                        <i class="ti ti-crown me-2"></i>
                        {{ __('Upgrade Now - 1 BHD') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        {{ __('Maybe Later') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> 