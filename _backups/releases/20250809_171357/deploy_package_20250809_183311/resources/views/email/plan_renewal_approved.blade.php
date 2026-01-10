@component('mail::message')
# 🎉 Plan Renewal Request Approved!

Dear **{{ $user->name }}**,

Great news! Your plan renewal request has been **approved** by our administrators.

## Plan Details
- **Plan Name:** {{ $plan->name }}
- **Price:** {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}
- **Duration:** {{ ucfirst($plan->duration) }}
- **Business Limit:** {{ $plan->business == '-1' ? 'Unlimited' : $plan->business }} businesses

## What's Next?
Your plan has been automatically activated and you now have full access to all the features included in your new plan. You can start using all the services immediately.

@component('mail::button', ['url' => route('home'), 'color' => 'success'])
Access Your Dashboard
@endcomponent

## Plan Features
- ✅ {{ count($plan->getThemes()) }} Premium Themes
- ✅ {{ $plan->business == '-1' ? 'Unlimited' : $plan->business }} Business Cards
- ✅ {{ $plan->max_users }} Users
- ✅ {{ $plan->storage_limit }} GB Storage
@if($plan->enable_custdomain == 'on')
- ✅ Custom Domain Support
@endif
@if($plan->enable_custsubdomain == 'on')
- ✅ Custom Subdomain Support
@endif
@if($plan->enable_branding == 'on')
- ✅ Branding Removal
@endif
@if($plan->pwa_business == 'on')
- ✅ PWA Business Features
@endif
@if($plan->enable_qr_code == 'on')
- ✅ QR Code Generation
@endif
@if($plan->enable_chatgpt == 'on')
- ✅ ChatGPT Integration
@endif
@if($plan->enable_wallet == 'on')
- ✅ Digital Wallet Integration
@endif

## Need Help?
If you have any questions or need assistance with your new plan features, please don't hesitate to contact our support team.

Thank you for choosing our services!

Best regards,<br>
**{{ $mail_header }}** Team

---
*This is an automated message. Please do not reply to this email.*
@endcomponent 