# Expired Plan Feature Implementation

## Overview
This feature provides a dedicated page for users whose plans have expired, allowing them to request plan renewal with a single renewal option and a notes panel for requesting additional features.

## Features Implemented

### 1. Expired Plan Page (`/plan/expired`)
- **Route**: `GET /plan/expired`
- **Controller**: `PlanController@expiredPlan`
- **View**: `resources/views/plan/expired.blade.php`

### 2. Renewal Request Processing
- **Route**: `POST /plan/request-renewal`
- **Controller**: `PlanController@requestRenewal`
- **Model**: Uses existing `plan_request` model

### 3. Automatic Redirection
- **Middleware**: `CheckPlan` middleware updated to redirect expired users to the expired plan page
- **Navigation**: Added "Plan Expired" link in sidebar for users with expired plans

## Key Components

### Controller Methods

#### `expiredPlan()`
- Checks if user's plan is actually expired
- Retrieves current plan information
- Gets a default renewal plan (lowest priced non-lifetime plan)
- Returns the expired plan view

#### `requestRenewal()`
- Validates renewal request data
- Creates a new plan request record
- Stores user notes for feature requests
- Returns success/error messages

### View Features

#### Single Renewal Option
- Displays only one renewal plan (not multiple options)
- Shows plan details: name, description, price, duration, features
- Pre-selected radio button for easy submission

#### Notes Panel
- Large textarea for additional feature requests
- List of available features users can request:
  - Custom Domain
  - Custom Subdomain
  - Branding Removal
  - PWA Business Features
  - QR Code Generation
  - ChatGPT Integration
  - Digital Wallet Integration
  - Additional Storage Space
  - Priority Support

#### User Experience
- Clear expiration warning with alert icon
- Current plan information display
- Information cards about processing time, security, and support
- Responsive design for mobile devices

### Middleware Integration

#### Updated CheckPlan Middleware
- Redirects users with expired plans to `/plan/expired` instead of `/plans/index`
- Maintains existing AJAX response handling
- Preserves error message functionality

### Navigation Integration

#### Sidebar Link
- Automatically appears for users with expired plans
- Uses warning icon to draw attention
- Links directly to expired plan page

## Database Schema

Uses existing tables:
- `users` table with `plan_expire_date` field
- `plans` table for plan information
- `plan_request` table for storing renewal requests

## Usage Flow

1. **User with expired plan** tries to access protected routes
2. **CheckPlan middleware** detects expired plan and redirects to `/plan/expired`
3. **Expired plan page** displays current plan info and single renewal option
4. **User fills notes** requesting additional features if needed
5. **User submits renewal request** via form
6. **System creates plan request** record with pending status
7. **Admin can review** and approve/reject the request via existing plan request management

## Security Features

- CSRF protection on form submission
- Input validation for notes and plan ID
- User authentication required for all routes
- XSS protection middleware applied

## Customization Options

### Renewal Plan Selection Logic
The default renewal plan is currently set to the lowest-priced non-lifetime plan. This can be customized in the `expiredPlan()` method:

```php
$renewalPlan = Plan::where('price', '>', 0)
                  ->where('duration', '!=', 'Lifetime')
                  ->orderBy('price', 'asc')
                  ->first();
```

### Available Features List
The list of available features users can request is defined in the view and can be easily modified.

### Processing Time
The information card mentions "24-48 hours" processing time, which can be updated in the view.

## Testing

To test the feature:

1. **Find a user with expired plan**:
   ```php
   $user = User::where('plan_expire_date', '<', date('Y-m-d'))->first();
   ```

2. **Access the expired plan page**:
   ```
   GET /plan/expired
   ```

3. **Submit a renewal request**:
   ```
   POST /plan/request-renewal
   ```

## Future Enhancements

- Email notifications to admins when renewal requests are submitted
- Automatic plan activation upon admin approval
- Integration with payment gateways for immediate renewal
- Custom renewal plan recommendations based on user history 