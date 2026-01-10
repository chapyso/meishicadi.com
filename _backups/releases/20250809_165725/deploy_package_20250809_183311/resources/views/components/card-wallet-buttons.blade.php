@if($walletEnabled)
<div class="wallet-section" style="margin: 20px 0; padding: 20px; background: rgba(255,255,255,0.95); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h4 style="text-align: center; margin-bottom: 20px; color: #333; font-size: 18px;">
        <i class="fas fa-wallet" style="color: #007bff;"></i> Add to Digital Wallet
    </h4>
    
    <div class="wallet-buttons" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <!-- Apple Wallet -->
        <div class="wallet-button-container" style="text-align: center;">
            @if(isset($existingPasses['apple']))
                <a href="{{ route('wallet.apple.download', $existingPasses['apple']->pass_id) }}" 
                   style="display: inline-block; text-decoration: none;">
                    <img src="{{ asset('assets/wallet-badges/apple-wallet-badge.svg') }}" 
                         alt="Add to Apple Wallet" 
                         style="height: 50px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                </a>
                <div style="margin-top: 8px;">
                    <small style="color: #28a745; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Generated
                    </small>
                </div>
            @else
                <button type="button" 
                        onclick="generateWalletPass('apple', {{ $business->id }})"
                        style="border: none; background: transparent; cursor: pointer; padding: 0;">
                    <img src="{{ asset('assets/wallet-badges/apple-wallet-badge.svg') }}" 
                         alt="Add to Apple Wallet" 
                         style="height: 50px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: transform 0.2s;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'">
                </button>
                <div style="margin-top: 8px;">
                    <small style="color: #6c757d;">Tap to generate</small>
                </div>
            @endif
        </div>
        
        <!-- Google Wallet -->
        <div class="wallet-button-container" style="text-align: center;">
            @if(isset($existingPasses['google']))
                <a href="{{ $googleWalletService->getPassSaveUrl($existingPasses['google']) }}" 
                   target="_blank"
                   style="display: inline-block; text-decoration: none;">
                    <img src="{{ asset('assets/wallet-badges/google-wallet-badge.svg') }}" 
                         alt="Add to Google Wallet" 
                         style="height: 50px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2);">
                </a>
                <div style="margin-top: 8px;">
                    <small style="color: #28a745; font-weight: 600;">
                        <i class="fas fa-check-circle"></i> Generated
                    </small>
                </div>
            @else
                <button type="button" 
                        onclick="generateWalletPass('google', {{ $business->id }})"
                        style="border: none; background: transparent; cursor: pointer; padding: 0;">
                    <img src="{{ asset('assets/wallet-badges/google-wallet-badge.svg') }}" 
                         alt="Add to Google Wallet" 
                         style="height: 50px; width: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); transition: transform 0.2s;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'">
                </button>
                <div style="margin-top: 8px;">
                    <small style="color: #6c757d;">Tap to generate</small>
                </div>
            @endif
        </div>
    </div>
    
    <div style="text-align: center; margin-top: 15px;">
        <small style="color: #6c757d; font-size: 12px;">
            <i class="fas fa-info-circle"></i> 
            Add this business card to your digital wallet for easy access and sharing
        </small>
    </div>
</div>

<script>
function generateWalletPass(walletType, businessId) {
    const button = event.target;
    const originalSrc = button.src;
    
    // Show loading state
    button.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQwIiBoZWlnaHQ9IjQwIiB2aWV3Qm94PSIwIDAgMTQwIDQwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cmVjdCB3aWR0aD0iMTQwIiBoZWlnaHQ9IjQwIiByeD0iOCIgZmlsbD0iI2Y4ZjlmYSIvPgo8dGV4dCB4PSI3MCIgeT0iMjYiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGZpbGw9IiM2Yzc1N2QiIGZvbnQtZmFtaWx5PSJBcmlhbCwgc2Fucy1zZXJpZiIgZm9udC1zaXplPSIxMiIgZm9udC13ZWlnaHQ9IjYwMCI+TG9hZGluZy4uLjwvdGV4dD4KPC9zdmc+';
    button.style.cursor = 'not-allowed';
    
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
            // Show success message
            showWalletMessage('success', data.message);
            
            // Reload page after 2 seconds to show new buttons
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showWalletMessage('error', data.error || 'An error occurred');
            // Reset button
            button.src = originalSrc;
            button.style.cursor = 'pointer';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showWalletMessage('error', 'An error occurred while generating the wallet pass');
        // Reset button
        button.src = originalSrc;
        button.style.cursor = 'pointer';
    });
}

function showWalletMessage(type, message) {
    // Create message element
    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 9999;
        max-width: 300px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        ${type === 'success' ? 'background: #28a745;' : 'background: #dc3545;'}
    `;
    messageDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}
</script>
@endif 