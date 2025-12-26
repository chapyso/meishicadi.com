<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Meishicadi Wallet Pass is Ready!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
            border-radius: 12px;
            background-color: white;
            padding: 15px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .tagline {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 25px;
        }
        
        .business-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border-left: 4px solid #667eea;
        }
        
        .business-info {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .business-logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 20px;
            border: 3px solid #667eea;
        }
        
        .business-details h3 {
            font-size: 20px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .business-details p {
            color: #6c757d;
            font-size: 14px;
        }
        
        .wallet-options {
            display: flex;
            gap: 15px;
            margin: 30px 0;
            flex-wrap: wrap;
        }
        
        .wallet-button {
            flex: 1;
            min-width: 200px;
            padding: 15px 20px;
            border-radius: 8px;
            text-decoration: none;
            text-align: center;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .apple-wallet {
            background: linear-gradient(135deg, #000000 0%, #333333 100%);
            color: white;
        }
        
        .apple-wallet:hover {
            background: linear-gradient(135deg, #333333 0%, #000000 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .google-wallet {
            background: linear-gradient(135deg, #4285f4 0%, #34a853 100%);
            color: white;
        }
        
        .google-wallet:hover {
            background: linear-gradient(135deg, #34a853 0%, #4285f4 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(66, 133, 244, 0.3);
        }
        
        .features {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        
        .features h4 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .feature-list {
            list-style: none;
        }
        
        .feature-list li {
            padding: 8px 0;
            color: #6c757d;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        
        .feature-list li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 10px;
            font-size: 16px;
        }
        
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer p {
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .social-links {
            margin-top: 20px;
        }
        
        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        
        .reminder {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        
        .reminder h4 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .reminder p {
            color: #856404;
            font-size: 14px;
        }
        
        @media (max-width: 600px) {
            .wallet-options {
                flex-direction: column;
            }
            
            .wallet-button {
                min-width: auto;
            }
            
            .business-info {
                flex-direction: column;
                text-align: center;
            }
            
            .business-logo {
                margin-right: 0;
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('custom/img/logo.png') }}" alt="Meishicadi" class="logo">
            <h1>Your Wallet Pass is Ready!</h1>
            <p class="tagline">"Your Digital Business Card, Always at Your Fingertips"</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Hello <strong>{{ $business->user->name }}</strong>,</p>
                <p>Great news! Your {{ $walletType == 'apple' ? 'Apple' : 'Google' }} Wallet pass for <strong>{{ $business->title }}</strong> has been successfully generated and is ready to use.</p>
            </div>
            
            <!-- Business Card Preview -->
            <div class="business-card">
                <div class="business-info">
                    @if($business->logo)
                        <img src="{{ \App\Models\Utility::get_file('card_logo/') . '/' . $business->logo }}" 
                             alt="{{ $business->title }}" 
                             class="business-logo">
                    @else
                        <div class="business-logo" style="background-color: #667eea; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                            {{ strtoupper(substr($business->title, 0, 2)) }}
                        </div>
                    @endif
                    <div class="business-details">
                        <h3>{{ $business->title }}</h3>
                        <p>{{ $business->designation ?? 'Business Professional' }}</p>
                        @if($business->phone)
                            <p>📞 {{ $business->phone }}</p>
                        @endif
                        @if($business->email)
                            <p>✉️ {{ $business->email }}</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- QR Code Section -->
            <div class="qr-section">
                <h4 style="color: #2c3e50; margin-bottom: 15px; text-align: center;">
                    <i class="fas fa-qrcode"></i> Quick Access QR Code
                </h4>
                <div style="text-align: center; margin: 20px 0;">
                    <div style="display: inline-block; padding: 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <img src="data:image/png;base64,{{ base64_encode(\QrCode::format('png')->size(200)->generate($downloadUrl)) }}" 
                             alt="Wallet Pass QR Code" 
                             style="max-width: 200px; height: auto;">
                    </div>
                </div>
                <p style="text-align: center; color: #6c757d; font-size: 14px; margin-top: 15px;">
                    Scan this QR code with your phone's camera to quickly add the wallet pass
                </p>
            </div>
            
            <!-- Wallet Options -->
            <div class="wallet-options">
                @if($walletType == 'apple' || $walletType == 'both')
                    <a href="{{ $downloadUrl }}" class="wallet-button apple-wallet">
                        <img src="{{ url('assets/wallet-badges/apple-wallet-badge.svg') }}" 
                             alt="Add to Apple Wallet" 
                             style="height: 40px; width: auto;">
                    </a>
                @endif
                
                @if($walletType == 'google' || $walletType == 'both')
                    <a href="{{ $googleWalletService->getPassSaveUrl($walletPass) }}" 
                       class="wallet-button google-wallet" 
                       target="_blank">
                        <img src="{{ url('assets/wallet-badges/google-wallet-badge.svg') }}" 
                             alt="Add to Google Wallet" 
                             style="height: 40px; width: auto;">
                    </a>
                @endif
            </div>
            
            <!-- Features -->
            <div class="features">
                <h4>🚀 What You Can Do With Your Wallet Pass:</h4>
                <ul class="feature-list">
                    <li>Share your business card instantly with a tap</li>
                    <li>Always have your contact info handy</li>
                    <li>Professional presentation at networking events</li>
                    <li>Quick access to your business details</li>
                    <li>Works offline - no internet required</li>
                    <li>Automatic updates when you modify your card</li>
                </ul>
            </div>
            
            <!-- Reminder -->
            <div class="reminder">
                <h4>💡 Pro Tip</h4>
                <p>Share this email with your clients and colleagues so they can easily add your business card to their digital wallets too!</p>
            </div>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                If you have any questions or need assistance, please don't hesitate to contact our support team.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p><strong>Meishicadi</strong></p>
            <p>Your Digital Business Card Solution</p>
            <p>Transform the way you network and share your business information</p>
            
            <div class="social-links">
                <a href="https://meishicadi.com">Website</a> |
                <a href="mailto:support@meishicadi.com">Support</a> |
                <a href="https://meishicadi.com/contact">Contact</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px; opacity: 0.8;">
                This email was sent to {{ $business->user->email }}. 
                If you didn't request this wallet pass, please ignore this email.
            </p>
        </div>
    </div>
</body>
</html> 