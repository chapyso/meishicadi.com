@component('mail::message')
# New Expired Plan Renewal Request

**User:** {{ $user->name }} ({{ $user->email }})  
**Requested Plan:** {{ $plan->name }}  
**Request Date:** {{ \App\Models\Utility::getDateFormated($planRequest->request_date, true) }}

## Plan Details
- **Plan Name:** {{ $plan->name }}
- **Price:** {{ env('CURRENCY_SYMBOL') ? env('CURRENCY_SYMBOL') : '$' }}{{ $plan->price }}
- **Duration:** {{ ucfirst($plan->duration) }}
- **Business Limit:** {{ $plan->business == '-1' ? 'Unlimited' : $plan->business }}

## User's Current Status
- **Current Plan:** {{ $user->currentPlan ? $user->currentPlan->name : 'No Plan' }}
- **Plan Expired Date:** {{ $user->plan_expire_date ? date('d-m-Y', strtotime($user->plan_expire_date)) : 'N/A' }}

@if($planRequest->notes)
## Additional Notes & Feature Requests
{{ $planRequest->notes }}
@endif

@component('mail::button', ['url' => route('plan_request.index')])
View All Plan Requests
@endcomponent

@component('mail::button', ['url' => route('response.request', [$planRequest->id, 1]), 'color' => 'success'])
Approve Request
@endcomponent

@component('mail::button', ['url' => route('response.request', [$planRequest->id, 0]), 'color' => 'error'])
Reject Request
@endcomponent

Thanks,<br>
{{ $mail_header }}
@endcomponent 