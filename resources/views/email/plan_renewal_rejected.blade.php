@component('mail::message')
# Plan Renewal Request Update

Dear **{{ $user->name }}**,

We have reviewed your plan renewal request for **{{ $plan->name }}** and unfortunately, we are unable to approve it at this time.

## Request Details
- **Requested Plan:** {{ $plan->name }}
- **Price:** {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}
- **Duration:** {{ ucfirst($plan->duration) }}

## Next Steps
You can still access our services by:

1. **Upgrading through our regular payment system** - Visit our plans page to upgrade using standard payment methods
2. **Contacting our support team** - If you have special circumstances, our team can assist you
3. **Exploring alternative plans** - We have various plans that might better suit your needs

@component('mail::button', ['url' => route('plans.index'), 'color' => 'primary'])
View Available Plans
@endcomponent

@component('mail::button', ['url' => route('plan.expired'), 'color' => 'secondary'])
Submit New Request
@endcomponent

## Need Assistance?
If you have any questions about this decision or would like to discuss alternative options, please don't hesitate to contact our support team. We're here to help you find the best solution for your needs.

Thank you for your understanding.

Best regards,<br>
**{{ $mail_header }}** Team

---
*This is an automated message. Please do not reply to this email.*
@endcomponent 