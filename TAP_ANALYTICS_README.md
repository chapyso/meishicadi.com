# Tap Analytics Feature

## Overview

The Tap Analytics feature provides comprehensive tracking and analytics for business card interactions across the platform. It tracks every tap or interaction a user receives on their digital business card, providing both users and super admins with actionable insights.

## Features

### For Users
- **Tap Analytics Dashboard**: View detailed analytics for their business cards
- **Real-time Tracking**: Monitor tap activity as it happens
- **Multiple Time Periods**: View data for 7 days, 30 days, 90 days, or 1 year
- **Device & Browser Analytics**: Understand how users interact with their cards
- **Geographic Insights**: See which countries are engaging with their cards
- **Suspicious Activity Alerts**: Get notified of potential spam or bot activity

### For Super Admins
- **System-wide Analytics**: View tap activity across all businesses and users
- **Advanced Filtering**: Filter by user, business, time period, and more
- **Export Functionality**: Export data in CSV or Excel format
- **Top Performing Cards**: Identify the most successful business cards
- **Suspicious Activity Management**: Flag and resolve suspicious taps
- **Weekly Email Reports**: Automated reports sent to admins

## Data Collected

For each tap interaction, the system collects:

- **Basic Information**:
  - User ID and Business ID
  - Card ID (for multiple cards per business)
  - Timestamp
  - IP Address

- **Tap Source**:
  - QR Code scan
  - NFC interaction
  - Direct link click
  - Direct page access

- **Device Information**:
  - Device type (mobile, tablet, desktop)
  - Operating system
  - Browser type and version
  - User agent string

- **Geographic Data**:
  - Country
  - City
  - Region

- **Marketing Data**:
  - UTM source, medium, and campaign
  - Referrer URL

- **Security**:
  - Suspicious activity detection
  - Bot detection

## Installation & Setup

### 1. Database Migration
The feature includes a migration that creates the `tap_analytics` table:

```bash
php artisan migrate
```

### 2. Routes
The following routes are automatically added:

```php
// User routes
Route::get('tap-analytics', [TapAnalyticsController::class, 'userAnalytics'])->name('tap-analytics.user');
Route::get('tap-analytics/api/data', [TapAnalyticsController::class, 'getAnalyticsData'])->name('tap-analytics.api.data');

// Admin routes
Route::get('admin/tap-analytics', [TapAnalyticsController::class, 'adminAnalytics'])->name('tap-analytics.admin');
Route::get('tap-analytics/export', [TapAnalyticsController::class, 'exportAnalytics'])->name('tap-analytics.export');
Route::post('admin/tap-analytics/resolve/{id}', [TapAnalyticsController::class, 'resolveSuspiciousTap'])->name('tap-analytics.resolve');

// API route for recording taps
Route::post('tap-analytics/record', [TapAnalyticsController::class, 'recordTap'])->name('tap-analytics.record');
```

### 3. JavaScript Integration
Include the tap analytics tracking script in your business card pages:

```html
<script src="/js/tap-analytics.js"></script>
```

### 4. Email Reports (Optional)
To enable weekly email reports, add to your cron jobs:

```bash
# Weekly report (every Monday at 9 AM)
0 9 * * 1 cd /path/to/your/app && php artisan tap-analytics:send-report --type=weekly

# Monthly report (first day of month at 9 AM)
0 9 1 * * cd /path/to/your/app && php artisan tap-analytics:send-report --type=monthly
```

## Usage

### For Users

1. **Access Analytics**: Navigate to your dashboard and click on "Tap Analytics"
2. **Select Business**: Choose which business card to analyze
3. **Choose Time Period**: Select from 7 days, 30 days, 90 days, or 1 year
4. **View Insights**: Explore charts, tables, and metrics

### For Admins

1. **Access Admin Analytics**: Navigate to Admin Dashboard → Tap Analytics
2. **Apply Filters**: Filter by user, business, or time period
3. **Export Data**: Use the export button to download CSV/Excel files
4. **Manage Suspicious Activity**: Review and resolve flagged taps

### API Usage

To manually record a tap via API:

```javascript
fetch('/tap-analytics/record', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify({
        business_id: 123,
        tap_source: 'QR',
        card_id: 'card_001'
    })
});
```

## File Structure

```
app/
├── Models/
│   └── TapAnalytics.php                 # Main analytics model
├── Http/Controllers/
│   └── TapAnalyticsController.php       # Controller for analytics
├── Exports/
│   └── TapAnalyticsExport.php           # Export functionality
└── Console/Commands/
    └── SendTapAnalyticsReport.php       # Email report command

resources/views/
├── tap_analytics/
│   ├── user_dashboard.blade.php         # User analytics view
│   └── admin_dashboard.blade.php        # Admin analytics view
└── emails/
    └── tap_analytics_report.blade.php   # Email template

public/js/
└── tap-analytics.js                     # Frontend tracking script

database/migrations/
└── 2025_08_03_192957_create_tap_analytics_table.php
```

## Configuration

### GeoIP Integration (Optional)
For accurate geographic data, integrate with a GeoIP service:

1. Install a GeoIP package (e.g., `geoip2/geoip2`)
2. Update the `getLocationInfo()` method in `TapAnalyticsController`
3. Configure your GeoIP database

### Email Configuration
Ensure your Laravel email configuration is set up for automated reports:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Your App Name"
```

## Security Features

### Suspicious Activity Detection
The system automatically detects:

- **Rapid Taps**: More than 10 taps from the same IP within 5 minutes
- **Bot Detection**: User agents containing bot keywords
- **Manual Flagging**: Admins can manually flag suspicious activity

### Data Privacy
- IP addresses are stored for security but can be anonymized
- Geographic data is optional and can be disabled
- Users can request data deletion

## Performance Optimization

### Database Indexes
The migration includes optimized indexes for:
- User and business combinations
- Creation timestamps
- Tap sources
- Device types
- Countries

### Caching
Consider implementing caching for frequently accessed analytics:
- Daily/weekly/monthly summaries
- Top performing cards
- Geographic distributions

## Troubleshooting

### Common Issues

1. **No Data Showing**: Ensure the tracking script is loaded and the business ID is correctly set
2. **Migration Errors**: Check if the table already exists and drop it before running migrations
3. **Export Failures**: Ensure the Excel package is installed: `composer require maatwebsite/excel`

### Debug Mode
Enable debug logging for tap analytics:

```php
// In TapAnalyticsController
Log::info('Tap recorded', $tapData);
```

## Future Enhancements

### Planned Features
- **Real-time WebSocket Updates**: Live dashboard updates
- **Advanced Segmentation**: Filter by demographics, behavior, etc.
- **A/B Testing Integration**: Track performance of different card designs
- **Predictive Analytics**: Forecast future tap trends
- **Mobile App Integration**: Native app analytics

### Customization Options
- **Custom Metrics**: Add business-specific tracking
- **White-label Reports**: Customize email templates
- **API Rate Limiting**: Configure limits for tap recording
- **Data Retention Policies**: Automatic data cleanup

## Support

For technical support or feature requests, please contact the development team or create an issue in the project repository.

## License

This feature is part of the business card platform and follows the same licensing terms as the main application. 