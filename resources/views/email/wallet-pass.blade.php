<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'apple' ? 'Apple Wallet Business Card' : 'Google Wallet Business Card' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            width: 60px;
            height: 60px;
            background: #007AFF;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo i {
            color: white;
            font-size: 24px;
        }
        .title {
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            font-size: 16px;
        }
        .content {
            margin: 30px 0;
        }
        .business-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .business-name {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .business-title {
            color: #666;
            font-size: 14px;
        }
        .action-buttons {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin: 10px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #007AFF;
            color: white;
        }
        .btn-primary:hover {
            background: #0056CC;
        }
        .btn-secondary {
            background: #34A853;
            color: white;
        }
        .btn-secondary:hover {
            background: #2E7D32;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .wallet-icon {
            font-size: 20px;
            margin-right: 8px;
        }
        .admin-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($isAdminCopy)
            <div class="admin-notice">
                <strong>📧 ADMIN COPY:</strong> This is a backup copy of a wallet pass email sent to {{ $walletPass->email }}.
            </div>
        @endif

        <div class="header">
            <div class="logo">
                <i class="fas fa-wallet"></i>
            </div>
            <h1 class="title">
                @if($type === 'apple')
                    🍎 Apple Wallet Business Card
                @else
                    🤖 Google Wallet Business Card
                @endif
            </h1>
            <p class="subtitle">Your digital business card is ready for your wallet!</p>
        </div>

        <div class="content">
            @if($isAdminCopy)
                <p>Hello Admin,</p>
                <p>This is a backup copy of a wallet pass email that was sent to <strong>{{ $walletPass->email }}</strong>.</p>
            @else
                <p>Hello!</p>
            @endif
            
            <p>Your {{ $type === 'apple' ? 'Apple Wallet' : 'Google Wallet' }} business card has been successfully generated and is ready to be added to your digital wallet.</p>

            <div class="business-info">
                <div class="business-name">{{ $walletPass->business->title }}</div>
                <div class="business-title">{{ $walletPass->business->designation ?? 'Professional' }}</div>
                @if($walletPass->business->sub_title)
                    <div class="business-title">{{ $walletPass->business->sub_title }}</div>
                @endif
            </div>

            <p>This digital business card includes:</p>
            <ul>
                <li>📱 Full contact information</li>
                <li>🏢 Company details</li>
                <li>📧 Email and phone</li>
                <li>🔗 Direct link to your digital profile</li>
                <li>📊 QR code for easy sharing</li>
            </ul>
        </div>

        <div class="action-buttons">
            @if($type === 'apple')
                <a href="{{ $downloadUrl }}" class="btn btn-primary">
                    <i class="fas fa-download wallet-icon"></i>
                    Download Apple Wallet Pass
                </a>
                <p style="margin-top: 15px; font-size: 14px; color: #666;">
                    📱 Tap the button above to download your .pkpass file, then open it on your iPhone to add to Apple Wallet.
                </p>
            @else
                <a href="{{ $googleWalletUrl }}" class="btn btn-secondary">
                    <i class="fas fa-plus wallet-icon"></i>
                    Add to Google Wallet
                </a>
                <p style="margin-top: 15px; font-size: 14px; color: #666;">
                    📱 Tap the button above to add your business card to Google Wallet on your Android device.
                </p>
            @endif
        </div>

        <div class="footer">
            <p>This wallet pass was generated by <strong>Meishicadi</strong></p>
            <p>Generated on: {{ $walletPass->created_at->format('F j, Y \a\t g:i A') }}</p>
            @if($isAdminCopy)
                <p style="margin-top: 10px; font-size: 12px; color: #999;">
                    🔒 This is an administrative backup copy for record-keeping purposes.
                </p>
            @else
                <p style="margin-top: 10px; font-size: 12px; color: #999;">
                    💡 Tip: You can generate new wallet passes anytime you need them.
                </p>
            @endif
        </div>
    </div>
</body>
</html> 