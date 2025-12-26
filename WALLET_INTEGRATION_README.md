# Apple Wallet & Google Wallet Integration for Meishicadi

This document provides a comprehensive guide for the Apple Wallet (PassKit) and Google Wallet integration feature for the Meishicadi platform.

## 🚀 Features Implemented

### ✅ Core Functionality
- **Premium Feature**: Wallet integration is available as a premium add-on for subscribed users
- **Apple Wallet Support**: Generate .pkpass files for iPhone and Apple Watch
- **Google Wallet Support**: Create wallet objects via Google Wallet API
- **Admin Management**: Complete admin panel for managing wallet passes
- **Email Notifications**: Automatic email notifications when passes are generated
- **Analytics**: Track download and usage statistics

### ✅ User Experience
- **Dashboard Integration**: "Add to Wallet" button appears on user dashboard for eligible users
- **Plan-based Access**: Only users with wallet-enabled plans can access the feature
- **Easy Generation**: One-click generation for both Apple and Google Wallet passes
- **Email Delivery**: Users receive professional email with wallet pass links
- **Automatic Updates**: Passes update when business information changes

### ✅ Admin Features
- **Pass Management**: View, activate, revoke, and manage all wallet passes
- **Statistics Dashboard**: View total passes, active passes, and platform breakdown
- **Email Resend**: Ability to resend wallet pass emails to users
- **Download Tracking**: Monitor pass download and usage statistics

## 📋 Requirements

### Apple Wallet Requirements
- Apple Developer Account
- Pass Type ID certificate (.p12 file)
- Team Identifier
- Pass Type Identifier

### Google Wallet Requirements
- Google Cloud Project
- Service Account with Wallet API access
- Issuer ID
- Private key for JWT signing

## 🔧 Installation & Setup

### 1. Database Setup
The required database tables have been created:
- `plans` table updated with `enable_wallet` column
- `wallet_passes` table created for storing pass data

### 2. Configuration
Add the following environment variables to your `.env` file:

```env
# Apple Wallet Configuration
APPLE_WALLET_ENABLED=true
APPLE_WALLET_CERTIFICATE_PATH=/path/to/your/certificate.p12
APPLE_WALLET_CERTIFICATE_PASSWORD=your_certificate_password
APPLE_WALLET_TEAM_IDENTIFIER=your_team_identifier
APPLE_WALLET_PASS_TYPE_IDENTIFIER=pass.com.yourcompany.businesscard

# Google Wallet Configuration
GOOGLE_WALLET_ENABLED=true
GOOGLE_WALLET_SERVICE_ACCOUNT_EMAIL=your-service-account@project.iam.gserviceaccount.com
GOOGLE_WALLET_PRIVATE_KEY="-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n"
GOOGLE_WALLET_ISSUER_ID=your_issuer_id
GOOGLE_WALLET_CLASS_ID=business_card

# General Wallet Settings
WALLET_PASS_EXPIRES_AFTER_DAYS=365
WALLET_PASS_MAX_DOWNLOADS=1000
```

### 3. File Structure
```
app/
├── Http/Controllers/
│   └── WalletController.php
├── Mail/
│   └── WalletPassEmail.php
├── Models/
│   └── WalletPass.php
└── Services/
    ├── AppleWalletService.php
    └── GoogleWalletService.php

config/
└── wallet.php

database/migrations/
├── 2024_01_15_000000_add_enable_wallet_to_plans_table.php
└── 2024_01_15_000001_create_wallet_passes_table.php

resources/views/
├── wallet/
│   ├── user/
│   │   └── index.blade.php
│   ├── options.blade.php
│   └── admin/
│       └── index.blade.php
└── emails/
    └── wallet-pass.blade.php
```

## 🎯 Usage Guide

### For Users

1. **Access Wallet Feature**
   - Users must have a plan with wallet feature enabled
   - "Manage Wallet" button appears on dashboard for eligible users
   - Wallet navigation item appears in sidebar menu

2. **Wallet Dashboard**
   - Access `/wallet` to see all your businesses
   - Each business card shows Apple and Google Wallet options
   - Visual indicators show which wallet passes are already generated

3. **Generate Wallet Pass**
   - Click Apple or Google button on any business card
   - Loading modal shows generation progress
   - Success modal provides direct download/open links
   - Pass is generated and email is sent automatically

4. **Add to Device**
   - **Apple Wallet**: Download .pkpass file and open with Apple Wallet
   - **Google Wallet**: Click link to open in Google Wallet app

### For Administrators

1. **Enable Wallet Feature**
   - Go to Plans management
   - Edit a plan and enable "Add to Wallet" toggle
   - Save the plan

2. **Manage Wallet Passes**
   - Access admin panel at `/admin/wallet`
   - View all generated passes
   - Toggle pass status (active/revoked)
   - Resend emails to users
   - View download statistics

3. **Monitor Usage**
   - Dashboard shows total passes, active passes
   - Platform breakdown (Apple vs Google)
   - Download and usage tracking

## 🔒 Security Features

- **Plan-based Access**: Only premium users can access wallet features
- **Authentication**: All wallet operations require user authentication
- **Token-based Downloads**: Apple Wallet downloads use secure tokens
- **Admin Permissions**: Wallet management requires admin privileges

## 📧 Email Templates

The system includes professional email templates that:
- Display business card information
- Provide direct links to add passes to wallets
- Include helpful tips and instructions
- Are fully customizable and translatable

## 🔄 API Endpoints

### User Endpoints
- `GET /wallet` - User wallet dashboard (list all businesses)
- `GET /wallet/{businessId}` - Show wallet options for specific business
- `POST /wallet/apple/{businessId}` - Generate Apple Wallet pass
- `POST /wallet/google/{businessId}` - Generate Google Wallet pass

### Admin Endpoints
- `GET /admin/wallet` - Admin wallet management
- `POST /admin/wallet/{passId}/toggle-status` - Toggle pass status
- `POST /admin/wallet/{passId}/resend-email` - Resend email

### API Endpoints
- `GET /api/wallet/apple/download/{passId}` - Download Apple Wallet pass
- `POST /api/wallet/apple/webhook` - Apple Wallet webhook

## 🛠 Troubleshooting

### Common Issues

1. **Apple Wallet Pass Not Generating**
   - Verify certificate path and password
   - Check Team Identifier and Pass Type Identifier
   - Ensure OpenSSL is installed on server

2. **Google Wallet Pass Not Generating**
   - Verify service account credentials
   - Check Issuer ID and permissions
   - Ensure Google Wallet API is enabled

3. **Email Not Sending**
   - Check mail configuration
   - Verify user email addresses
   - Check mail logs for errors

### Debug Mode
Enable debug logging by adding to `.env`:
```env
LOG_LEVEL=debug
```

## 📈 Analytics & Reporting

The system tracks:
- Total passes generated
- Pass downloads and usage
- Platform preferences (Apple vs Google)
- User engagement metrics
- Pass expiration and renewal rates

## 🔮 Future Enhancements

### Planned Features
- **NFC Support**: Add NFC tap functionality to wallet passes
- **Auto-updates**: Real-time pass updates when business info changes
- **Advanced Analytics**: Detailed usage analytics and reporting
- **Bulk Operations**: Generate multiple passes at once
- **Custom Branding**: Allow custom colors and branding for passes
- **Dark Mode**: Support for dark/light mode wallet styling

### Technical Improvements
- **Caching**: Implement caching for better performance
- **Queue System**: Use queues for pass generation
- **Webhook Improvements**: Enhanced webhook handling
- **API Rate Limiting**: Implement rate limiting for API endpoints

## 📞 Support

For technical support or questions about the wallet integration:
- Check the troubleshooting section above
- Review server logs for error messages
- Contact the development team with specific error details

## 📄 License

This wallet integration feature is part of the Meishicadi platform and follows the same licensing terms.

---

**Note**: This feature requires proper Apple Developer and Google Cloud Platform setup. Please ensure all certificates and API keys are properly configured before enabling the feature in production. 