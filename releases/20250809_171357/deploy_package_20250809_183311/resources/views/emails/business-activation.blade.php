<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Your Meishicadi Business Card</title>
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
        
        .activation-section {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
        }
        
        .activation-section h3 {
            color: #856404;
            margin-bottom: 15px;
            font-size: 20px;
        }
        
        .activation-section p {
            color: #856404;
            margin-bottom: 25px;
            font-size: 16px;
        }
        
        .activate-btn {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }
        
        .activate-btn:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
            color: white;
            text-decoration: none;
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
        
        .urgency {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border: 1px solid #dc3545;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }
        
        .urgency h4 {
            color: #721c24;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .urgency p {
            color: #721c24;
            font-size: 14px;
        }
        
        @media (max-width: 600px) {
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
            <h1>Activate Your Business Card</h1>
            <p class="tagline">"Your Digital Business Card, Always at Your Fingertips"</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="greeting">
                <p>Hello <strong>{{ $user->name ?? 'there' }}</strong>,</p>
                <p>Welcome to Meishicadi! Your business card <strong>{{ $business->title ?? 'Your Business' }}</strong> has been created and is ready for activation.</p>
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
                        <h3>{{ $business->title ?? 'Business' }}</h3>
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
            
            <!-- Activation Section -->
            <div class="activation-section">
                <h3>🚀 Ready to Go Live?</h3>
                <p>Your business card is waiting to be activated. Once activated, you'll be able to:</p>
                <ul class="feature-list">
                    <li>Share your digital business card with clients</li>
                    <li>Add it to Apple Wallet and Google Wallet</li>
                    <li>Track views and interactions</li>
                    <li>Update your information anytime</li>
                </ul>
                <br>
                <a href="{{ $activationUrl }}" class="activate-btn">
                    <i class="fas fa-rocket"></i> Activate My Business Card
                </a>
            </div>
            
            <!-- Urgency Section -->
            <div class="urgency">
                <h4>⏰ Don't Wait Too Long!</h4>
                <p>Activate your business card within 24 hours to ensure it's properly indexed and ready for your clients to discover.</p>
            </div>
            
            <!-- Features -->
            <div class="features">
                <h4>🎯 What You Get With Meishicadi:</h4>
                <ul class="feature-list">
                    <li>Professional digital business card with your branding</li>
                    <li>QR code for instant sharing</li>
                    <li>Analytics to track card views and interactions</li>
                    <li>Mobile-responsive design</li>
                    <li>Easy sharing via WhatsApp, email, and social media</li>
                    <li>Wallet integration (Apple Wallet & Google Wallet)</li>
                    <li>Real-time updates when you modify your information</li>
                </ul>
            </div>
            
            <p style="margin-top: 30px; color: #6c757d; font-size: 14px;">
                If you have any questions or need assistance with activation, please don't hesitate to contact our support team.
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
                This email was sent to {{ $user->email ?? 'your email' }}. 
                If you didn't create this business card, please ignore this email.
            </p>
        </div>
    </div>
</body>
</html> 